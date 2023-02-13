<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guests;
use App\Models\UserVerify;
use App\Models\Rating;
use App\Models\FavQuotes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Client\ConnectionException;
use \Carbon\Carbon;
use Auth;
use Str;

class MenuController extends Controller
{
    use AuthenticatesUsers;

    public function submitRating(Request $request){
        try {
            if(isset($_POST['rmovie'])){
                $url="https://api.themoviedb.org/3/movie/";
                $media_type='Movie';
            }
            else{
                $url="https://api.themoviedb.org/3/tv/";
                $media_type='Tv Show';
            }

            $value=json_encode(['value'=>$request->input('rate')]);
            $session=Auth::guard('user')->user()->session;
            $guest_id=Auth::guard('user')->user()->id;

            if(!is_null(Rating::where('title',$request->title)->first())){
                return redirect()->back()->with('failure','You have already rated this movie/show..');
            }
            else if(Rating::where('created_at','<',Carbon::now())->count()>10){
                return redirect()->back()->with('warning','You have reached your daily limit (10 ratings) !');
            }

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->send('POST', $url.$request->id.'/rating?api_key='.config('services.tmdb.api').'&guest_session_id='.$session, [
                    'body' => $value
                ])->json(); 

            if($response['success']){
                Rating::create([
                    'guest_id'=>$guest_id,
                    'media_type'=>$media_type,
                    'title'=>$request->title,
                    'show_id'=>$request->id,
                    'value'=>$request->input('rate') ]);

                return redirect()->back()->with('success','Rating Submitted!');

            }
            else {
                return redirect()->back()->with('failure',$response['status_message']);
            }
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }

    }


    public function showRatingsForm($page){
        abort_if($page<1||$page>500,404);
        $guest=Auth::guard('user')->user();
        $ratings=Rating::where('guest_id',$guest->id)->get();

        $ratings=collect($ratings)->map(function($rating){
            return collect($rating)->merge([
                'value'=>$rating['value'].'.0/10',
                'linkToPage' => $rating['media_type'] === 'Movie' ? route('movies.show', $rating['show_id']) : route('tv.show', $rating['show_id']),
            ]);
        });
        $ratings=$ratings->forPage($page, 6);

        return view('users.ratings',['ratings'=>$ratings,'page'=>$page]);
    }

    public function showAvatarForm(){

        return view('users.changeAvatar');
    }


    public function changeAvatar(Request $request){
        $user=Auth::guard('user')->user();
        if(isset($_POST['remove'])){

            File::delete($user->avatar);
            $user=Guests::where('id',$user->id)->first();
            $user->avatar=null;
            $user->save();
            return redirect()->back();

        }
        else{
            if ($request->hasFile('avatar')){
                $avatar=$request->file('avatar');

                $validator =  Validator::make($request->all(),[
                'avatar' => 'mimes:jpeg,jpg,png',
                ]);

                if ($validator->fails()){
                    return redirect()->back()->with('warning',$validator->errors()->first());
                }

                $user=Guests::where('id',$user->id)->first();
                File::delete($user->avatar);
                $subfolder = Str::random(12).$user->username.Str::random(12);
                $path = Storage::disk('public')->put('avatars/'.$subfolder, $avatar);
                $user->avatar='storage/'.$path;

                $user->save();

                return redirect()->back()->with('success','Avatar changed..');
            }

            else{
                return redirect()->back()->with('warning','No file was chosen !!');
            }

        }
    }


    public function showQuotesForm(){
        $guest_id=Auth::guard('user')->user()->id;
        $favs=FavQuotes::where('guest_id',$guest_id)->paginate(6);

        return view('users.quotes',['favs'=>$favs]);
    }


    public function getAnimeQuote(Request $request){

        try{
            $user_id=Auth::guard('user')->user()->id;
            $guest=Guests::where('id',$user_id)->first();
            $favs=FavQuotes::where('guest_id',$user_id)->paginate(6);


            if($guest->quote_limit > 0){
                $guest->quote_limit = $guest->quote_limit - 1 ;
                $guest->save();
                $data=Http::get('https://animechan.vercel.app/api/random');
                return view('users.quotes',['data'=>$data,'favs'=>$favs]);
            }
            
            return redirect()->back()->with('warning','You have exceeded your quotes limit, come back again after: '.$guest->session_expiry);
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }
    }


    public function addToFav(Request $request){

        $guest_id=Auth::guard('user')->user()->id;
        $fav=FavQuotes::create([
                    'guest_id'=>$guest_id,
                    'anime'=>$request->anime,
                    'character'=>$request->character,
                    'quote'=>$request->quote]);


        return response()->json([ 'id'=>$fav->id,
                                  'anime'=>$fav->anime,
                                  'character'=>$fav->character,
                                  'quote'=>$fav->quote],200);
    }

    public function removeFromFav($id){

        $guest_id=Auth::guard('user')->user()->id;
        $fav=FavQuotes::where('id',$id)->delete();

        if($fav){
            return redirect()->to('/animeQuotes/favs')->with('success','Removed successfully..');
        }
        else{
            return redirect()->to('/animeQuotes/favs')->with('warning','There is no such quote..');
        }

        
    }
}
