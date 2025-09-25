<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\ValueObjects\UserId;
use App\Models\User as EloquentUser;
use App\Domain\ValueObjects\Money;

class UserRepository implements UserRepositoryInterface
{
    public function findById(UserId $userId): ?User
    {
        $eloquentUser = EloquentUser::find($userId->value());

        if (!$eloquentUser) {
            return null;
        }

        return $this->toDomainEntity($eloquentUser);
    }

    public function findByEmail(string $email): ?User
    {
        $eloquentUser = EloquentUser::where('email', $email)->first();

        if (!$eloquentUser) {
            return null;
        }

        return $this->toDomainEntity($eloquentUser);
    }

    public function save(User $user): void
    {
        $eloquentUser = EloquentUser::find($user->getId()->value());

        if (!$eloquentUser) {
            $eloquentUser = new EloquentUser();
        }

        $eloquentUser->name = $user->getName();
        $eloquentUser->email = $user->getEmail();
        $eloquentUser->balance = $user->getBalance()->getAmount();

        if ($user->getPassword()) {
            $eloquentUser->password = $user->getPassword();
        }

        $eloquentUser->save();
    }

    public function delete(UserId $id): void
    {
        EloquentUser::where('id', $id->value())->delete();
    }

    public function findEloquentById(int $id): ?EloquentUser
    {
        return EloquentUser::findOrFail($id);
    }

    public function findByIdForUpdate(int $id): ?EloquentUser
    {
        return EloquentUser::lockForUpdate()->findOrFail($id);
    }

    public function create(array $data): EloquentUser
    {
        return EloquentUser::create($data);
    }

    public function updateBalanceById(int $userId, float $balance): void
    {
        EloquentUser::where('id', $userId)->update(['balance' => $balance]);
    }

    private function toDomainEntity(EloquentUser $eloquentUser): User
    {
        return new User(
            UserId::fromInt($eloquentUser->id),
            $eloquentUser->name,
            $eloquentUser->email,
            new Money((float) $eloquentUser->balance),
        );
    }
}
