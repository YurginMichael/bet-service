<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        $bet = $this->resource;

        return [
            'id' => is_array($bet) ? $bet['id'] : $bet->id,
            'event_title' => is_array($bet) ? $bet['event_title'] : $bet->event_title,
            'outcome' => is_array($bet) ? $bet['outcome'] : $bet->outcome,
            'amount' => (float) (is_array($bet) ? $bet['amount'] : $bet->amount),
            'coefficient' => (float) (is_array($bet) ? $bet['coefficient'] : $bet->coefficient),
            'potential_win' => (float) (is_array($bet) ? $bet['potential_win'] : $bet->potential_win),
            'status' => is_array($bet) ? $bet['status'] : $bet->status,
            'created_at' => is_array($bet) ? $bet['created_at'] : $bet->created_at,
        ];
    }
}
