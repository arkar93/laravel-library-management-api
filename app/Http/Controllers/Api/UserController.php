<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\DeleteUserRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Requests\Users\ViewUserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(ViewUserRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $users = $this->userService->getAllUsers($perPage);

        $response = [
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'limit' => $users->perPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ];

        return response()->json($response);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return response()->json($user, 201);
    }

    public function show(ViewUserRequest $request, User $user)
    {
        $user->load('roles');
        $user->role = $user->roles->pluck('name');
        unset($user->roles);
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($user, $request->validated());
        return response()->json($user, 200);
    }

    public function destroy(DeleteUserRequest $request, User $user)
    {
        if ($user->id === 1 && $user->hasRole('Super-Admin')) {
            return response()->json(['message' => 'Not allow to delete Super-Admin.'], 403);
        }

        $this->userService->deleteUser($user);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
