<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\EventResource;
use Illuminate\Contracts\Support\Responsable;

class EventItemResponse implements Responsable
{
    public function __construct(private array $event)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'event' => EventResource::make($this->event)
        ]);
    }
}
