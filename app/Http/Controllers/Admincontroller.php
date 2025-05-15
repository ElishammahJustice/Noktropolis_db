<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Role, User};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Hash, Auth};

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        // Ensure user has the 'access_admin' ability
        $this->middleware(function (Request $request, $next) {
            /** @var User|null $user */
            $user = $request->user();

            if (! $user || ! $user->hasAbility('access_admin')) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            return $next($request);
        });
    }

    // Admin-only registration
    public function adminRegister(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasAbility('create_admin')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users',
            'password'             => 'required|string|min:8|confirmed',
            'password_confirmation'=> 'required'
        ]);

        $adminRole = Role::where('slug', 'admin')->firstOrFail();

        $newAdmin = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => bcrypt($request->password),
            'role_id'     => $adminRole->id,
            'is_approved' => true,
        ]);

        return response()->json([
            'message' => 'Admin created successfully.',
            'admin'   => $newAdmin,
        ], 201);
    }

    // List pending approvals
    public function pendingApprovals(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasAbility('view_pending_users')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $users = User::where('is_approved', false)->with('role')->get();
        return response()->json($users);
    }

    // Approve a user
    public function approveUser(Request $request, $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasAbility('approve_user')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $target = User::findOrFail($id);
        $target->update(['is_approved' => true]);

        return response()->json(['message' => 'User approved successfully.']);
    }

    // Assign a role
    public function assignRole(Request $request, $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! $user->hasAbility('assign_roles')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->validate(['role' => 'required|string|exists:roles,slug']);

        $targetRole = Role::where('slug', $request->role)->firstOrFail();
        $target     = User::findOrFail($id);
        $target->update(['role_id' => $targetRole->id]);

        return response()->json([
            'message' => 'Role assigned successfully.',
            'user'    => $target->load('role'),
        ]);
    }
}
