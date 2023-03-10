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
use App\Events\SuccessfullRegister;
use Illuminate\Http\Client\ConnectionException;
use \Carbon\Carbon;
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
            $guest->quote_limit=5;
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
                'username' => 'unique:Guests,username|regex:/^[a-zA-Z0-9][a-zA-Z0-9.@!#$%&*]+$/',
                'email' => 'unique:Guests,email',
                'avatar' => 'mimes:jpeg,jpg,png',
            ]);

            if ($validator->fails()){
                return redirect()->back()->with('warning',$validator->errors()->first());
            }

            

            $guest = Guests::create([
                        'firstname'=>$firstname,
                        'lastname'=>$lastname,
                        'username'=>$username,
                        'email'=>$email,
                        'password'=>$password
                    ]);
            if(isset($avatar)){
                $subfolder = Str::random(12).$username.Str::random(12);
                $path = Storage::disk('public')->put('avatars/'.$subfolder, $avatar);
                $guest->avatar='storage/'.$path;
            }

            $guest->save();

            // event(new SuccessfullRegister($guest));

            SuccessfullRegister::dispatch($guest);

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
    
}
