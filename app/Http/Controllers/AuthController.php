<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginUserRequest $request){
        $request -> validated($request -> all());

        if(!Auth::attempt($request -> only(['username', 'password']))){
            return redirect("login")->withSuccess("Špatné přihlašovací údaje");
        }

        $user = User::where('username', $request -> username) -> first();
        #token => $user -> createToken('API Token of ' . $user -> name) -> plainTextToken

        return redirect()->intended('dashboard')->withSUccess('Přihlášeno');
    }

    public function logout(){
        Auth::user() -> currentAccessToken() -> delete();
        Session::flush();
        Auth::logout();
        return Redirect('login')->withSuccess('Byli jste úspěšně odhlášeni');
    }

    public function dashboard(){
        if(Auth::check()){
            return view('auth.dashboard');
        }
        return redirect("login")->withSuccess('Neoprávněný přístup');
    }

    public function getUser(){
        $user = Auth::user();

        return $this -> success([
            'user' => $user,
            'user_details' => $userDetails
        ]);
    }
}
