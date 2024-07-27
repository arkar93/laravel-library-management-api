<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUserAndRoles();
    }


    protected function seedUserAndRoles()
    {
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        
        $superAdminRole = Role::create(['name' => 'Super-Admin', 'guard_name' => 'api']);
        $user->assignRole($superAdminRole);

        $this->createPermissions();
        
        $user->createToken('Personal Access Token')->accessToken;
    }

    protected function createPermissions()
    {
        $permissions = array_merge(
            config('permissions.users'),
            config('permissions.roles'),
            config('permissions.category'),
            config('permissions.books')
        );

        return collect($permissions)->map(function ($permissionName) {
            return Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
        });
    }
}
