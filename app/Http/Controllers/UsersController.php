<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use \Carbon\Carbon;

class UsersController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
                        dd("hello");
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
        $session=Http::get('https://api.themoviedb.org/3/authentication/guest_session/new'.'?api_key='.config('services.tmdb.api'))->json();
        $guest->session=$session['guest_session_id'];
        $guest->session_expiry=Carbon::parse($session['expires_at']);
        $guest->save();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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



        $user = new Guests;
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;
        if(isset($avatar)){
            $path = Storage::disk('public')->put('avatars', $avatar);
            $user->avatar='storage/'.$path;
        }
        $user->save();

        return view('users.login');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();     
        return view('users.login');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $response = Http::withHeaders(['Content-Type' => 'application/json'])
        //     ->send('POST', 'https://example.com', [
        //         'body' => '{ test: 1 }'
        //     ])->json();
    }
}
