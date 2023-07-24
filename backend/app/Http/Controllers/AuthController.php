<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $userResource = new UserResource($user);

        return response([
            'user' => $userResource,
            'token' => $token
        ], 200);
    }


    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        return response([
            'message' => 'Logged out'
        ]);
    }
}
