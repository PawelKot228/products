<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthorizationController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (auth()->attempt($request->validated())) {
            /** @var User $user */
            $user = auth()->user();
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
