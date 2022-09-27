<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required']
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Invalid input'], 422);
        }

        if (auth()->attempt($request->only(['username', 'password']))) {
            $user = User::where('username', $request->username)->first();
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'successfully logged out'], 200);
    }

    public function me()
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(auth()->user(), 200);
    }

    public function resetPassword(Request $request)
    {
        if (auth()->user() === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validate = Validator::make($request->all(), [
            'old_password' => ['required'],
            'new_password' => ['required']
        ]);
        if ($validate->fails()) {
            return response()->json(['message' => 'Invalid input'], 422);
        }

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json(['message' => 'old password did not match'], 422);
        }

        $user = User::findOrFail(auth()->user()->id);
        $user->update(['password' => Hash::make($request->new_password)]);
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'reset success, user logged out'], 200);
    }
}