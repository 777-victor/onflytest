<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{

    /**
     * Handle an incoming login request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();
        $token = $user->createToken('firstToken')->plainTextToken;
        $request->session()->regenerate();

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }


    /**
     * Handle logout
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ]);
    }
}
