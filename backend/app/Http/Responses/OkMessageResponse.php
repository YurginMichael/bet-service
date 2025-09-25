<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class OkMessageResponse implements Responsable
{
    public function __construct(private string $message)
    {
    }

    public function toResponse($request)
    {
        return response()->json(['message' => $this->message]);
    }
}
