<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getAllUsers($perPage = 10)
    {
        return User::with('roles:name')->paginate($perPage)->through(function ($user) {
            $user->role = $user->roles->pluck('name');
            unset($user->roles);
            return $user;
        });
    }

    public function getUserByEmailAndPassword($email, $password)
    {
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['role'])) {
            $role = Role::findByName($data['role'], 'api');
            $user->assignRole($role);
        }
        return $user;
    }

    public function updateUser(User $user, $data)
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['role']) && $user->id !== 1) {
            $this->updateUserRole($user, $data['role']);
        }

        $user->save();

        return $user;
    }

    protected function updateUserRole(User $user, string $role)
    {
        $validRole = Role::where('name', $role)->first();
        if ($validRole) {
            $user->syncRoles([$validRole]);
        }
    }

    public function deleteUser(User $user)
    {
        $user->delete();
    }
}
