<?php

declare(strict_types=1);

namespace App\Http\Requests\Bets;

use Illuminate\Foundation\Http\FormRequest;

class CreateBetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|integer|exists:events,id',
            'outcome' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:10000',
        ];
    }
}


