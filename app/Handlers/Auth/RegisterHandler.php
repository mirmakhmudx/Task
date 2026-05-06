<?php

namespace App\Handlers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\RegisterService;

class RegisterHandler
{
    public function __construct(
        private readonly RegisterService $registerService
    ) {}

    public function handle(RegisterRequest $request): User
    {
        return $this->registerService->handle(
            $request->validated()
        );
    }
}
