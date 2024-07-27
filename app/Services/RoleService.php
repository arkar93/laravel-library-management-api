<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public function getAllRoles($perPage = 10)
    {
        return Role::with('permissions')->paginate($perPage);
    }

    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name'], 'guard_name' => 'api']);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->givePermissionTo($permissions);
        }

        return $role;
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return $role;
    }

    public function deleteRole(Role $role)
    {
        $role->delete();
    }
}
