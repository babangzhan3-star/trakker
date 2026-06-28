<?php

namespace App\Mock;

use App\Services\MockService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * MOCK API — Base repository with shared methods.
 * All data comes from storage/mock/*.json files, no database.
 */
abstract class BaseRepository
{
    protected static string $key;

    /**
     * Wrap raw stdClass in MockObject for route() compatibility.
     */
    protected static function wrap(object $data): MockObject
    {
        return $data instanceof MockObject ? $data : new MockObject($data);
    }

    public static function all(): Collection
    {
        return MockService::load(static::$key)->map(fn($item) => static::wrap($item));
    }

    public static function find(int $id): ?MockObject
    {
        $item = MockService::find(static::$key, $id);
        return $item ? static::wrap($item) : null;
    }

    public static function findOrFail(int $id): MockObject
    {
        $item = MockService::findOrFail(static::$key, $id);
        return static::wrap($item);
    }

    public static function paginate(Collection $items, int $perPage = 10, ?string $pageName = 'page'): LengthAwarePaginator
    {
        return MockService::paginate($items, $perPage, $pageName);
    }

    public static function filter(Collection $items, array $filters): Collection
    {
        return MockService::filter($items, $filters);
    }

    public static function search(Collection $items, ?string $query, array $fields): Collection
    {
        return MockService::search($items, $query, $fields);
    }

    public static function paginateAll(int $perPage = 10): LengthAwarePaginator
    {
        return static::paginate(static::all(), $perPage);
    }
}
