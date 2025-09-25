<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        $user = $this->resource;

        return [
            'id' => is_array($user) ? $user['id'] : $user->id,
            'name' => is_array($user) ? $user['name'] : $user->name,
            'email' => is_array($user) ? $user['email'] : $user->email,
            'balance' => (float) (is_array($user) ? $user['balance'] : $user->balance),
        ];
    }
}
