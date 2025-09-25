<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\RegisterUserDTO;
use App\Application\DTO\AuthResponseDTO;
use App\Application\DTO\UserResponseDTO;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(RegisterUserDTO $dto): AuthResponseDTO
    {
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'balance' => 1000.00,
        ]);

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
