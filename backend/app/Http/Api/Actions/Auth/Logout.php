<?php

declare(strict_types=1);

namespace App\Http\Api\Actions\Auth;

use App\Application\UseCases\LogoutUserUseCase;
use App\Http\Responses\OkMessageResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Logout
{
    public function __construct(private LogoutUserUseCase $logoutUserUseCase)
    {
    }

    public function __invoke(Request $request): JsonResponse|OkMessageResponse
    {
        $result = $this->logoutUserUseCase->execute($request);
        return new OkMessageResponse($result['message'] ?? 'OK');
    }
}
