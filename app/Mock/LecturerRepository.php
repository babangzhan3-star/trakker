<?php

namespace App\Mock;

/**
 * MOCK API — Lecturer repository. Reads from storage/mock/lecturers.json.
 */
class LecturerRepository extends BaseRepository
{
    protected static string $key = 'lecturers';

    public static function allSorted(): \Illuminate\Support\Collection
    {
        return static::all()->sortBy('nama')->values();
    }
}
