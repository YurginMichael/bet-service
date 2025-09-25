<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     */
    public function toArray($request): array
    {
        $event = $this->resource;

        return [
            'id' => is_array($event) ? $event['id'] : $event->id,
            'title' => is_array($event) ? $event['title'] : $event->title,
            'description' => is_array($event) ? $event['description'] : $event->description,
            'outcomes' => is_array($event) ? $event['outcomes'] : $event->outcomes,
            'starts_at' => is_array($event) ? $event['starts_at'] : $event->starts_at,
            'ends_at' => is_array($event) ? ($event['ends_at'] ?? null) : ($event->ends_at ?? null),
            'status' => is_array($event) ? $event['status'] : $event->status,
            'available_for_betting' => is_array($event) ? $event['available_for_betting'] : $event->available_for_betting,
        ];
    }
}
