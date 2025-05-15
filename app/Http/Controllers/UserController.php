<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Get all users (admin only)
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_users')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(User::all());
    }

    // Get single user (admin or self)
    public function show($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_user') && $user->id != $id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(User::findOrFail($id));
    }

    // Create user (admin only)
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('create_user')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $newUser = User::create($validated);

        return response()->json(['message' => 'User created successfully', 'user' => $newUser]);
    }

    // Update user (admin or self)
    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('edit_user') && $user->id != $id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $targetUser->update($validated);

        return response()->json(['message' => 'User updated successfully', 'user' => $targetUser]);
    }

    // Delete user (admin only)
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('delete_user')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $targetUser = User::findOrFail($id);
        $targetUser->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
