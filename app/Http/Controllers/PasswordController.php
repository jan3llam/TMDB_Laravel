<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guests;
use App\Models\UserVerify;
use Mail;
use Auth;
use Str;

class PasswordController extends Controller
{

    // normal password change 

    public function showPasswordForm()
    {    
        return view('users.password.changePassword');
    }

    public function changePassword(Request $request)
    {    

        $guest=Auth::guard('user')->user();
        $oldPass=$request->input('oldPass');
        $newPass=$request->input('newPass');


        if (Hash::check($oldPass,$guest->password)){
            $guest=Guests::where('id',$guest->id)->first();
            $guest->password=Hash::make($newPass);
            $guest->save();
            return redirect(route('users.login'))->with('success','Please sign in again with new password..');
        }

        return view('users.password.changePassword')->with('failure','Old password is wrong !!');

        
    }

    //redirect after google auth

    public function showSetupPassForm()
    {    
        return view('users.password.googleSetupPassword');
    }

    public function firstTimePassword(Request $request)
    {    

        $guest=Auth::guard('user')->user();
        $newPass=$request->input('newPass');

        $guest=Guests::where('id',$guest->id)->first();
        $guest->password=Hash::make($newPass);
        $guest->save();


        return redirect(route('movies.index'))->with('success','New Password Submitted !');
        
    }

    public function showForgetPassForm()
    {    

        return view('users.password.forgetPassword');
  
    }

    public function forgetPassword(Request $request)
    {    

        $guest=Guests::where('email',$request->email)->first();

        if(is_null($guest)){
            return view('users.password.forgetPassword')->with('failure','Email is not found !!');
        }

        
        $token = Str::random(32);

        UserVerify::create([
                  'passtoken' => $token, 
                  'guest_id' => $guest->id
                ]);

        Mail::send('emails.resetPasswordEmail', ['token' => $token], function($message) use($guest){
                  $message->to($guest->email);
                  $message->from('MandS.supp@gmail.com', 'Movies&Shows');
                  $message->subject('Reset Password Email');
              });

        return redirect(route('users.login'))->with('success','Please check your email for link..');

        
    }


    public function showResetPasswordForm($token)
    {
        return view('users.password.resetPassword',['token'=>$token]);
    }

    public function resetPassword(Request $request)
    {
        $verifyUser = UserVerify::where('passtoken', $request->token)->first();
        $guest = $verifyUser->guest;
        $guest=Guests::where('id',$guest->id)->first();

        $guest->password=Hash::make($request->input('newPass'));
        $guest->save();
            
      return redirect(route('users.login'))->with('success','Please sign in again with new password..');
    }
}
