<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function handleLogin(LoginUserRequest $request){
        $request -> validated($request -> all());

        if(!Auth::attempt($request -> only(['username', 'password']))){
            return redirect("login")->withSuccess("Špatné přihlašovací údaje");
        }

        $user = User::where('username', $request -> username) -> first();

        return redirect()->intended('home')->withSUccess('Přihlášeno');
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return Redirect('login')->withSuccess('Byli jste úspěšně odhlášeni');
    }

    public function getUser(){
        $user = Auth::user();

        return $this -> success([
            'user' => $user,
            'user_details' => $userDetails
        ]);
    }

    public function register(StoreUserRequest $request){
        $request -> validated($request -> all());

        $user = User::create([
            'username' => $request -> username,
            'password' => Hash::make($request -> password),
        ]);

        return $this -> response()->json([
            'user' => $user,
            'token' => $user -> createToken('API Token of ' . $user -> name) -> plainTextToken
        ]);
    }
}
