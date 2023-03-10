<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use Str;
use App\Models\UserVerify;
use App\Events\SuccessfullRegister;

class SendConfirmationEmail
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SuccessfullRegister  $event
     * @return void
     */
    public function handle(SuccessfullRegister $event)
    {

        $guest = $event->guest;

        $token = Str::random(64);

        UserVerify::create([
                  'emailtoken' => $token, 
                  'guest_id' => $guest->id
                ]);

        $response = Mail::send('emails.verificationEmail', ['token' => $token], function($message) use($guest){
                  $message->to($guest->email);
                  $message->from('MandS.supp@gmail.com', 'Movies&Shows');
                  $message->subject('Email Verification Mail');
              });

    }
}
