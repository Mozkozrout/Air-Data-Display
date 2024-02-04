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

    /**
     * Function that simply calls the login view
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Function that actually does the login through custom LoginUserRequest
     */
    public function handleLogin(LoginUserRequest $request){
        $request -> validated($request -> all());

        if(!Auth::attempt($request -> only(['username', 'password']))){
            return redirect("login")->withSuccess("Wrong Credentialls");
        }

        $user = User::where('username', $request -> username) -> first();

        return redirect()->intended('/')->withSUccess('Logged in');
    }

    /**
     * Simple logout function
     */
    public function logout(){
        Session::flush();
        Auth::logout();
        return Redirect('login')->withSuccess('Byli jste úspěšně odhlášeni');
    }

    /**
     * Function for registering new users which is not really all that utilised here, i used it only so i have one user in the database to use
     */
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
