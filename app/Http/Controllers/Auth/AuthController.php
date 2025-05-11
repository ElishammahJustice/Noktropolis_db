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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', 'in:customer,vendor,admin'] // Role selection
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Assign default role (Customer) if no role is provided
        $roleName = $validated['role'] ?? 'customer';
        $role = Role::where('slug', $roleName)->first();

        if (!$role) {
            return response()->json(['error' => 'Invalid role selected.'], 400);
        }

        // Vendors require approval before activation
        $isApproved = ($roleName !== 'vendor');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => $role->id,
            'is_approved' => $isApproved,
        ]);

        // Auto-login only if approved
        if ($isApproved) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user->load('role'),
                'token' => $token
            ], 201);
        }

        return response()->json([
            'message' => 'Registration successful. Awaiting approval.',
            'user' => $user->load('role')
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if the user is approved
        if (!$user->is_approved) {
            return response()->json(['message' => 'Your account is awaiting approval.'], 403);
        }

        // Check if the user is suspended
        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Your account has been suspended. Please contact support.'], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('role'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No authenticated user found.'], 401);
        }

        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user_info(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $user->load('role');

        if ($user->status === 'suspended') {
            return response()->json(['message' => 'Your account has been suspended. Please contact support.'], 403);
        }

        return response()->json(['user' => $user]);
    }
}
