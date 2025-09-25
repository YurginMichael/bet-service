<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\DTO\CreateBetDTO;
use App\Application\DTO\BetResponseDTO;
use App\Domain\Exceptions\BusinessRuleException;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\BetRepositoryInterface;
use App\Domain\Repositories\TransactionRepositoryInterface;
use App\Models\FraudLog;
use Illuminate\Support\Facades\DB;

class CreateBetUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventRepositoryInterface $eventRepository,
        private BetRepositoryInterface $betRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {}

    public function execute(CreateBetDTO $dto): array {
        $user = $this->userRepository->findEloquentById($dto->userId);

        $event = $this->eventRepository->findEloquentById($dto->eventId);

        if (!$event->isAvailableForBetting()) {
            throw new BusinessRuleException('Event is not available for betting.', 400);
        }

        $coefficient = $event->getCoefficientForOutcome($dto->outcome);
        if ($coefficient === null) {
            throw new BusinessRuleException('Invalid outcome for this event.', 400);
        }

        if ($user->balance < $dto->amount) {
            throw new BusinessRuleException('Insufficient balance', 400);
        }

        try {
            DB::beginTransaction();

            $user = $this->userRepository->findByIdForUpdate($dto->userId);

            if ($user->balance < $dto->amount) {
                DB::rollBack();
                throw new BusinessRuleException('Insufficient balance due to concurrent request.', 400);
            }

            $existingBet = $this->betRepository->findByIdempotencyKey($dto->idempotencyKey);
            if ($existingBet) {
                DB::rollBack();
                return [
                    'bet' => (new BetResponseDTO(
                        id: $existingBet->id,
                        eventTitle: $existingBet->event->title,
                        outcome: $existingBet->outcome,
                        amount: (float) $existingBet->amount,
                        coefficient: (float) $existingBet->coefficient,
                        potentialWin: (float) $existingBet->potential_win,
                        status: $existingBet->status,
                        createdAt: $existingBet->created_at->toISOString()
                    ))->toArray(),
                    'balance' => $user->balance,
                ];
            }

            $potentialWin = $dto->amount * $coefficient;

            $bet = $this->betRepository->create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'outcome' => $dto->outcome,
                'amount' => $dto->amount,
                'coefficient' => $coefficient,
                'potential_win' => $potentialWin,
                'status' => 'pending',
                'idempotency_key' => $dto->idempotencyKey,
            ]);

            $balanceBefore = $user->balance;
            $this->userRepository->updateBalanceById($user->id, $user->balance - $dto->amount);
            $user->refresh();

            $this->transactionRepository->create([
                'user_id' => $user->id,
                'bet_id' => $bet->id,
                'type' => 'bet_placed',
                'amount' => -$dto->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->balance,
                'description' => "Bet placed on {$event->title} - {$dto->outcome}",
            ]);

            DB::commit();

            return [
                'bet' => (new BetResponseDTO(
                    id: $bet->id,
                    eventTitle: $event->title,
                    outcome: $bet->outcome,
                    amount: (float) $bet->amount,
                    coefficient: (float) $bet->coefficient,
                    potentialWin: (float) $bet->potential_win,
                    status: $bet->status,
                    createdAt: $bet->created_at->toISOString()
                ))->toArray(),
                'balance' => $user->balance,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            FraudLog::logSuspiciousActivity(
                $dto->userId,
                'bet_creation_error',
                [
                    'error' => $e->getMessage(),
                    'event_id' => $dto->eventId,
                    'amount' => $dto->amount,
                    'outcome' => $dto->outcome,
                ],
                'high'
            );
            throw $e;
        }
    }
}
