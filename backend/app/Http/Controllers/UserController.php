<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('firstToken')->plainTextToken;
        $userResource = new UserResource($user);

        return response([
            'user' => $userResource,
            'token' => $token
        ], 201);
    }

    public function getAuthenticatedUser(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
