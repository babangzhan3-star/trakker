<?php

namespace App\Mock;

use App\Services\MockService;
use Illuminate\Support\Collection;

/**
 * MOCK API — User repository. Reads from storage/mock/users.json.
 */
class UserRepository extends BaseRepository
{
    protected static string $key = 'users';

    /**
     * Find user by email.
     */
    public static function findByEmail(string $email): ?object
    {
        return static::all()->firstWhere('email', $email);
    }

    /**
     * Get users except given IDs.
     */
    public static function exceptIds(array $ids): Collection
    {
        return static::all()->filter(fn($u) => !in_array($u->id, $ids))->values();
    }

    /**
     * Get user's project IDs from project_members.
     */
    public static function getUserProjectIds(int $userId): array
    {
        return MockService::load('project_members')
            ->where('user_id', $userId)
            ->pluck('project_id')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Decorate user with Carbon dates.
     */
    public static function decorate(object $user): object
    {
        $user->created_at = MockService::toCarbon($user->created_at ?? null);
        $user->updated_at = MockService::toCarbon($user->updated_at ?? null);
        $user->email_verified_at = MockService::toCarbon($user->email_verified_at ?? null);
        return $user;
    }
}
