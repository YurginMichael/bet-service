<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Auth;

use App\Application\DTO\LoginUserDTO;
use App\Application\UseCases\LoginUserUseCase;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\AuthResponse;
use Illuminate\Http\JsonResponse;

class Login
{
    public function __construct(private LoginUserUseCase $loginUserUseCase)
    {
    }

    public function __invoke(LoginRequest $request): JsonResponse|AuthResponse
    {
        $dto = LoginUserDTO::fromRequest($request->all());
        $result = $this->loginUserUseCase->execute($dto);

        return new AuthResponse($result->toArray());
    }
}
