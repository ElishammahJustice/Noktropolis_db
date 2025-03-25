<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all();

            if ($roles->isEmpty()) {
                return response()->json(["message" => "No roles found"], 404);
            }

            return response()->json(["roles" => $roles], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Error fetching roles"], 500);
        }
    }

    public function createRole(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255|unique:roles",
            "slug" => "required|string|max:255|unique:roles",
            "description" => "nullable|string|max:1000",
        ]);

        try {
            $role = Role::create($validated);
            return response()->json(["message" => "Role created successfully", "role" => $role], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => "Error creating role"], 500);
        }
    }

    public function getRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(["error" => "Role not found"], 404);
        }

        return response()->json(["role" => $role], 200);
    }

    public function updateRole(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(["error" => "Role not found"], 404);
        }

        $validated = $request->validate([
            "name" => "required|string|max:255|unique:roles,name," . $id,
            "slug" => "required|string|max:255|unique:roles,slug," . $id,
            "description" => "nullable|string|max:1000",
        ]);

        try {
            $role->update($validated);
            return response()->json(["message" => "Role updated successfully", "role" => $role], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Error updating role"], 500);
        }
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(["error" => "Role not found"], 404);
        }

        try {
            $role->delete();
            return response()->json(["message" => "Role deleted successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Error deleting role"], 500);
        }
    }
}
