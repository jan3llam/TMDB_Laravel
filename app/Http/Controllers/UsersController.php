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
                'username' => 'unique:Guests,username',
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
                $path = Storage::disk('public')->put('avatars', $avatar);
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


            return view('users.login')->with('success','Please sign in again..');
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
  
      return redirect(route('users.login'))->with('success',$message);
    }


    public function submitRating(Request $request)
    {
        try {
            if($_POST['rmovie']){
                $url="https://api.themoviedb.org/3/movie/";
                $media_type='movie';
            }
            else{
                $url="https://api.themoviedb.org/3/tv/";
                $media_type='tv';
            }

            $value=json_encode(['value'=>$request->input('rate')]);
            $session=Auth::guard('user')->user()->session;
            $guest_id=Auth::guard('user')->user()->id;

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->send('POST', $url.$request->id.'/rating?api_key='.config('services.tmdb.api').'&guest_session_id='.$session, [
                    'body' => $value
                ])->json(); 

            if($response->success){
                Rating::create([
                    'guest_id'=>$guest_id,
                    'media_type'=>$media_type,
                    'show_id'=>$request->id,
                    'value'=>$value ]);

                return redirect()->back()->with('success','Rating Submitted!');

            }
            else {
                return redirect()->back()->with('failure',$response->status_message);
            }
        }
        catch(ConnectionException $e){

            return redirect()->back()->with('failure','Connection Problem..');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();     
        return view('users.login');
    }

    
}
