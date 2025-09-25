<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use Illuminate\Http\Request;

class LogoutUserUseCase
{
    public function execute(Request $request): array
    {
        $request->user()->currentAccessToken()->delete();

        return ['message' => 'Successfully logged out'];
    }
}
