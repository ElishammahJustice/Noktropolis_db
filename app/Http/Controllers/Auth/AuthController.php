<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{Role, User};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Hash, Auth};
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
            'role'     => 'nullable|string|in:customer,vendor,admin',
        ]);

        $roleName = $request->role ?: 'customer';
        $role     = Role::where('slug', $roleName)->firstOrFail();

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role_id'     => $role->id,
            'is_approved' => ($roleName !== 'vendor'),
        ]);

        // Build payload
        $payload = [
            'user'      => $user->load('role'),
            'abilities' => $user->role->abilities->pluck('name'),
        ];

        // Auto-login only if approved
        if ($user->is_approved) {
            $payload['token'] = $user->createToken('auth-token')->plainTextToken;
        }

        return response()->json($payload, 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.']
            ]);
        }

        if (! $user->is_approved) {
            return response()->json(['message' => 'Account awaiting approval.'], 403);
        }

        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token'     => $token,
            'user'      => $user->load('role'),
            'abilities' => $user->role->abilities->pluck('name'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user_info(Request $request): JsonResponse
    {
        $user = $request->user()->load('role');

        return response()->json([
            'user'      => $user,
            'abilities' => $user->role->abilities->pluck('name'),
        ]);
    }
}
