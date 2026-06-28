<?php

namespace App\Models;

use App\Services\MockService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

#[Fillable([
    'nama_proyek', 'deskripsi', 'course_id', 'lecturer_id',
    'created_by', 'semester', 'kelas', 'deadline', 'status'
])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('role', 'kontribusi_persen')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // MOCK API: resolve from JSON instead of database
    public function resolveRouteBinding($value, $field = null)
    {
        $mock = MockService::find('projects', (int) $value);
        if (!$mock) {
            throw new ModelNotFoundException();
        }
        $mock = MockService::relateProject($mock);

        $instance = new static();
        $instance->id = $mock->id;
        $instance->nama_proyek = $mock->nama_proyek;
        $instance->deskripsi = $mock->deskripsi;
        $instance->course_id = $mock->course_id;
        $instance->lecturer_id = $mock->lecturer_id;
        $instance->created_by = $mock->created_by;
        $instance->semester = $mock->semester;
        $instance->kelas = $mock->kelas;
        $instance->deadline = $mock->deadline;
        $instance->status = $mock->status;
        $instance->created_at = $mock->created_at;
        $instance->updated_at = $mock->updated_at;
        $instance->tasks_count = $mock->tasks_count ?? 0;
        $instance->tasks_done_count = $mock->tasks_done_count ?? 0;
        $instance->members_count = $mock->members_count ?? 0;
        $instance->exists = true;

        // Set relasi
        $instance->setRelation('course', $mock->course ? (new Course())->resolveRouteBinding($mock->course->id) : null);
        $instance->setRelation('lecturer', $mock->lecturer ? (new Lecturer())->resolveRouteBinding($mock->lecturer->id) : null);
        $instance->setRelation('creator', $mock->creator ? (new User())->resolveRouteBinding($mock->creator->id) : null);
        $instance->setRelation('members', MockService::load('project_members')
            ->where('project_id', $mock->id)
            ->values()
            ->map(function ($pm) {
                $user = MockService::find('users', $pm->user_id);
                if (!$user) return null;
                $u = new User();
                $u->id = $user->id;
                $u->name = $user->name;
                $u->email = $user->email;
                $u->nim = $user->nim;
                $u->semester = $user->semester;
                $u->kelas = $user->kelas;
                $u->avatar = $user->avatar;
                $u->exists = true;
                $u->pivot = (object) [
                    'role' => $pm->role,
                    'kontribusi_persen' => $pm->kontribusi_persen,
                    'project_id' => $pm->project_id,
                    'user_id' => $pm->user_id,
                ];
                return $u;
            })->filter()->values());

        return $instance;
    }
}
