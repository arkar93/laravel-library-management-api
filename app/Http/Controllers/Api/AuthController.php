<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        $token = $user->createToken('PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userService->getUserByEmailAndPassword($request->email, $request->password);

        if ($user) {
            $token = $user->createToken('PassportAuth')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function userInfo()
    {
        $user = Auth::user();
        $user->load('roles');
        $user->role = $user->roles->pluck('name');
        unset($user->roles);
        return response()->json(['user' => $user], 200);
    }
}
