<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\BetResponseDTO;
use App\Domain\Repositories\BetRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserBetsUseCase
{
    public function __construct(
        private BetRepositoryInterface $betRepository
    ) {}

    public function execute(int $userId): array
    {
        $bets = $this->betRepository->getUserBets($userId);

        return [
            'bets' => $bets->map(function ($bet) {
                return (new BetResponseDTO(
                    id: $bet->id,
                    eventTitle: $bet->event->title,
                    outcome: $bet->outcome,
                    amount: (float) $bet->amount,
                    coefficient: (float) $bet->coefficient,
                    potentialWin: (float) $bet->potential_win,
                    status: $bet->status,
                    createdAt: $bet->created_at->toISOString()
                ))->toArray();
            }),
            'pagination' => [
                'current_page' => $bets->currentPage(),
                'last_page' => $bets->lastPage(),
                'per_page' => $bets->perPage(),
                'total' => $bets->total(),
            ]
        ];
    }
}
