<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index(): JsonResponse
    {
        $users = User::with('roles')->get();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => $users
        ], 200);
    }

    /**
     * Display a specific user.
     */
    public function show(User $user): JsonResponse
    {
        $user->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user
        ], 200);
    }

    /**
     * Assign or update a user's role.
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' assigned successfully.",
            'data' => [
                'user' => $user->load('roles')
            ]
        ], 200);
    }

    /**
     * Remove a specific role from a user.
     */
    public function removeRole(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        if (!$user->hasRole($request->role)) {
            return response()->json([
                'success' => false,
                'message' => "User does not have the role '{$request->role}'."
            ], 404);
        }

        $user->removeRole($request->role);

        return response()->json([
            'success' => true,
            'message' => "Role '{$request->role}' removed successfully.",
            'data' => [
                'user' => $user->load('roles')
            ]
        ], 200);
    }
}
