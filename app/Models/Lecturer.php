<?php

namespace App\Models;

use App\Services\MockService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

#[Fillable(['nama', 'gelar', 'nidn', 'email', 'no_telp'])]
class Lecturer extends Model
{
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // MOCK API: resolve from JSON instead of database
    public function resolveRouteBinding($value, $field = null)
    {
        $mock = MockService::find('lecturers', (int) $value);
        if (!$mock) {
            throw new ModelNotFoundException();
        }
        $instance = new static();
        $instance->id = $mock->id;
        $instance->nama = $mock->nama;
        $instance->gelar = $mock->gelar;
        $instance->nidn = $mock->nidn;
        $instance->email = $mock->email;
        $instance->no_telp = $mock->no_telp;
        $instance->created_at = MockService::toCarbon($mock->created_at ?? null);
        $instance->updated_at = MockService::toCarbon($mock->updated_at ?? null);
        $instance->projects_count = $mock->projects_count ?? 0;
        $instance->exists = true;
        return $instance;
    }
}
