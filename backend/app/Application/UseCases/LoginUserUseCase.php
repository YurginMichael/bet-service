<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\LoginUserDTO;
use App\Application\DTO\AuthResponseDTO;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;

class LoginUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(LoginUserDTO $dto): AuthResponseDTO
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResponseDTO(
            user: new UserResponseDTO(
                id: $user->id,
                name: $user->name,
                email: $user->email,
                balance: (float) $user->balance
            ),
            token: $token
        );
    }
}
