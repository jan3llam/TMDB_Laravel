<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;

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
            return ResponseController::error(-2,"User doesn't exists");
        }

        if (Hash::check($password,$guest->password)) {
            if (Auth::guard('user')->attempt(['id'=>$guest->id,"password"=>$password])){
                return redirect(route('movies.index'));
            }
        }
        else {
            return ResponseController::error(-2,"user and password doesn't match");
        }

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
            return ResponseController::error(-1,$validator->errors()->first());
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
