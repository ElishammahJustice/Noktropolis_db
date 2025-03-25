<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('admin');
    }

    // Admin-only Registration (Admins can create other Admins)
    public function adminRegister(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $adminRole->id,
            'is_approved' => true // Admins are approved by default
        ]);

        return response()->json(['message' => 'Admin created successfully.', 'admin' => $admin], 201);
    }

    // Fetch users awaiting approval
    public function pendingApprovals(): JsonResponse
    {
        $users = User::where('is_approved', false)->with('role')->get();
        return response()->json($users);
    }

    // Approve a user
    public function approveUser($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();
        return response()->json(['message' => 'User approved successfully.']);
    }

    // Assign role to user
    public function assignRole(Request $request, $id): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($id);
        $role = Role::where('name', $request->role)->firstOrFail();

        $user->role_id = $role->id;
        $user->save();

        return response()->json(['message' => 'Role assigned successfully.', 'user' => $user->load('role')]);
    }
}
