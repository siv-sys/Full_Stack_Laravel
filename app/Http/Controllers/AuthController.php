<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Registration successful.',
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
                'errors' => null,
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
            'message' => 'Login successful.',
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $userId = $user->id;

            if ($request->boolean('all_devices')) {
                $user->tokens()->delete();
            } else {
                $user->currentAccessToken()->delete();
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            Log::info('User logged out.', [
                'user_id' => $userId,
                'all_devices' => $request->boolean('all_devices'),
                'ip' => $request->ip(),
            ]);

            $response = response()->json([
                'success' => true,
                'data' => null,
                'message' => $request->boolean('all_devices')
                    ? 'Logged out from all devices.'
                    : 'Logged out successfully.',
            ]);

            foreach (['laravel_session', 'XSRF-TOKEN'] as $cookie) {
                $response->withoutCookie($cookie);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Logout failed.', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed. Please try again.',
                'errors' => null,
            ], 500);
        }
    }
}
