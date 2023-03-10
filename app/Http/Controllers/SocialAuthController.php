<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Guests;
use Auth;
use Str;
use App\Events\SuccessfullRegister;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class SocialAuthController extends Controller
{

    use AuthenticatesUsers;


    //redirect to google auth page
    public function redirectToProvider (){

        return Socialite::driver('google')->stateless()->redirect();


    }

    //obtain user info
    public function handleCallback(){
        try {

            $user = Socialite::driver('google')->stateless()->user();

        }
        catch(\Exception $e){
            return redirect(route('users.login'))->with('failure','Please try again later..');
        }

        $guest = Guests::where('google_id',$user->id)->first();

        if(is_null($guest)) {

            $firstname=explode(' ',$user->name)[0];
            $lastname=explode(' ',$user->name)[1];
            
            $guest = Guests::create([
                        'firstname'=>$firstname,
                        'lastname'=>$lastname,
                        'username'=>$user->name,
                        'email'=>$user->email,
                        'password'=>Str::random(20),
                        'google_id'=>$user->id
                    ]);

            app('App\Http\Controllers\UsersController')->assignSession($guest);

            Auth::guard('user')->login($guest);

            SuccessfullRegister::dispatch($guest);

            return redirect(route('users.google.setup'))->with('warning','Please setup your password ..');

        }

        else{

            Auth::guard('user')->login($guest);

            return redirect(route('movies.index'))->with('success','Login Successfully !');

        }


    }
}
