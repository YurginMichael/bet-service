<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Http\Resources\EventResource;
use Illuminate\Contracts\Support\Responsable;

class EventListResponse implements Responsable
{
    /**
     * @param iterable|array $events
     */
    public function __construct(private $events)
    {
    }

    public function toResponse($request)
    {
        return response()->json([
            'events' => EventResource::collection($this->events)
        ]);
    }
}
