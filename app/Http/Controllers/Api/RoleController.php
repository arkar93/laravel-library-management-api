<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\DeleteRoleRequest;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Requests\Roles\ViewPermissionRequest;
use App\Http\Requests\Roles\ViewRoleRequest;
use App\Services\RoleService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    public function index(ViewRoleRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $roles = $this->roleService->getAllRoles($perPage);

        $response = [
            'data' => $roles->items(),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'limit' => $roles->perPage(),
                'total_pages' => $roles->lastPage(),
                'total' => $roles->total(),
            ],
        ];

        return response()->json($response);
    }

    public function getPermissions(ViewPermissionRequest $request) {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->createRole($request->validated());

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ], 201);
    }

    public function show(ViewRoleRequest $request, $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if ($role->name === 'Super-Admin') {
            return response()->json(['message' => 'Not allow to update Super-Admin role.'], 403);
        }

        $role = $this->roleService->updateRole($role, $request->validated());

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role
        ], 200);
    }

    public function destroy(DeleteRoleRequest $request, $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if ($role->name === 'Super-Admin') {
            return response()->json(['message' => 'Not allow to delete Super-Admin role.'], 403);
        }

        $this->roleService->deleteRole($role);

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}

