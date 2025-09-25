<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Auth;

use App\Application\DTO\RegisterUserDTO;
use App\Application\UseCases\RegisterUserUseCase;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\AuthResponse;
use Illuminate\Http\JsonResponse;

class Register
{
    public function __construct(private RegisterUserUseCase $registerUserUseCase)
    {
    }

    public function __invoke(RegisterRequest $request): JsonResponse|AuthResponse
    {
        $dto = RegisterUserDTO::fromRequest($request->all());
        $result = $this->registerUserUseCase->execute($dto);

        return new AuthResponse($result->toArray());
    }
}
