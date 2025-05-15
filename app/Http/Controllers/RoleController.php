<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:sanctum');
        //$this->middleware(function ($request, $next) {
           // if (! $request->user()->hasAbility('manage_roles')) {
           //     return response()->json(['error' => 'Forbidden'], 403);
          //  }
          //  return $next($request);
       // });
    }

    public function index()
    {
        return response()->json(['roles' => Role::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'          => 'required|integer',
            'name'        => 'required|string|unique:roles,name',
            'slug'        => 'required|string|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        return response()->json(['role' => $role], 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $id,
            'slug'        => 'required|string|unique:roles,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json(['role' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Role deleted.']);
    }
}
