<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Service\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserRequest $request)
    {
        $attributes = $request->validated();

        $attributes['password'] = bcrypt($attributes['password']);
        $this->userService->create($attributes);

        return response()->json([
            'status' => 'success',
            'data' => $attributes
        ]);
    }
}
