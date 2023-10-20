<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        
        $username      = $request->input("username");
        $password   = $request->input("password");
        $auth       = Auth::attempt(['username' => $username, 'password' => $password]);        
        if(!$auth){
            return response([
                "message" => "Incorrect username and password"
            ],Response::HTTP_UNAUTHORIZED);
        }

        $user   = Auth::user();

        $token  = $user->createToken("token")->plainTextToken;

        $cookie     = Cookie("jwt",$token,60*24);

        return response([
            "message" => "success",
            "token"     => $token
        ])->withCookie($cookie);
    }
    public function user() {
        return Auth::user();
    }
}
