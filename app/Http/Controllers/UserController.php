<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Bad credentials'], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response(['token' => $token], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        // event(new \Illuminate\Auth\Events\Logout('sanctum', $user));

        return response()->noContent();
    }
    public function allUsers()
    {
        return User::all();
    }
}
