<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guests;
use App\Models\UserVerify;
use App\Models\Rating;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Client\ConnectionException;
use \Carbon\Carbon;
use Mail;
use Auth;
use Str;

class UsersController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('users.login');
    }

    public function login(Request $request){

        $email=$request->input('email');
        $password=$request->input('password');

        $guest = Guests::where('email', $email)->orWhere('username', $email)->first();
        if ($guest==null){
            return redirect(route('users.login'))->with('failure','User doesn\'t exist.');
        }

        if (Hash::check($password,$guest->password)) {
            if (Auth::guard('user')->attempt(['id'=>$guest->id,"password"=>$password])){
                $user=Auth::guard('user')->user();
                if(isset($user->session)){
                    if($user->session_expiry <= Carbon::now()){
                        $this->assignSession($guest);
                    }
                }
                else{
                    $this->assignSession($guest);
                } 
                return redirect(route('movies.index'))->with('success','You are logged in sucessfully.');
            }
        }
        else {
            return redirect(route('users.login'))->with('failure','User and Password doesn\'t match.');
        }

    }


    public function assignSession($guest){
        try{
            $session=Http::get('https://api.themoviedb.org/3/authentication/guest_session/new'.'?api_key='.config('services.tmdb.api'))->json();
            $guest->session=$session['guest_session_id'];
            $guest->session_expiry=Carbon::parse($session['expires_at']);
            $guest->save();
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegisterForm()
    {
        return view('users.signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {   
        try {
            $firstname=$request->input('firstname');
            $lastname=$request->input('lastname');
            $username=$request->input('username');
            $email=$request->input('email');
            $password=Hash::make($request->input('password'));


            if ($request->hasFile('avatar')){
                $avatar=$request->file('avatar');
            }


            $validator =  Validator::make($request->all(),[
                'firstname' => 'string|regex:/^[a-zA-Z]+$/',
                'lastname' => 'string|regex:/^[a-zA-Z]+$/',
                'username' => 'unique:Guests,username|regex:/^[a-zA-Z]{1}/',
                'email' => 'unique:Guests,email',
                'avatar' => 'mimes:jpeg,jpg,png',
            ]);

            if ($validator->fails()){
                return redirect()->back()->with('warning',$validator->errors()->first());
            }

            

            $user = Guests::create([
                        'firstname'=>$firstname,
                        'lastname'=>$lastname,
                        'username'=>$username,
                        'email'=>$email,
                        'password'=>$password
                    ]);
            if(isset($avatar)){
                $subfolder = Str::random(12).$username.Str::random(12);
                $path = Storage::disk('public')->put('avatars/'.$subfolder, $avatar);
                $user->avatar='storage/'.$path;
            }

            $user->save();

            $token = Str::random(64);

            UserVerify::create([
                  'emailtoken' => $token, 
                  'guest_id' => $user->id
                ]);

            Mail::send('emails.verificationEmail', ['token' => $token], function($message) use($user){
                  $message->to($user->email);
                  $message->from('MandS.supp@gmail.com', 'Movies&Shows');
                  $message->subject('Email Verification Mail');
              });


            return redirect(route('users.login'))->with('success','Check your mail for the verification link so you can start rating, please sign in again ..');
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }

    }


    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('emailtoken', $token)->first();
  
        $message = 'Sorry your email cannot be identified.';
  
        if(isset($verifyUser) ){
            $guest = $verifyUser->guest;
              
            if(!$guest->verified) {
                $verifyUser->guest->verified = 1;
                $verifyUser->guest->email_verified_at = Carbon::now();
                $verifyUser->guest->save();
                $message = "Your e-mail is verified, You can now enjoy your ratings.";
            } else {
                $message = "Your e-mail is already verified, You can now enjoy your ratings.";
            }
        }
  
      return redirect(route('movies.index'))->with('success',$message);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();     
        return redirect(route('movies.index'));
    }

    public function submitRating(Request $request)
    {
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
    
}
