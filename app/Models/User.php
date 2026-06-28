<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\MockService;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'nim', 'semester', 'kelas', 'avatar', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // MOCK API: resolve from JSON instead of database
    public function resolveRouteBinding($value, $field = null)
    {
        $mock = MockService::find('users', (int) $value);
        if (!$mock) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();
        }
        $mock = MockService::relateUser($mock);

        $instance = new static();
        $instance->id = $mock->id;
        $instance->name = $mock->name;
        $instance->email = $mock->email;
        $instance->nim = $mock->nim;
        $instance->semester = $mock->semester;
        $instance->kelas = $mock->kelas;
        $instance->avatar = $mock->avatar;
        $instance->email_verified_at = $mock->email_verified_at;
        $instance->password = $mock->password;
        $instance->remember_token = $mock->remember_token;
        $instance->created_at = $mock->created_at;
        $instance->updated_at = $mock->updated_at;
        $instance->exists = true;
        return $instance;
    }

    /**
     * Proyek yang dibuat oleh user (sebagai ketua).
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Proyek yang diikuti user (sebagai anggota).
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('role', 'kontribusi_persen')
                    ->withTimestamps();
    }

    /**
     * Tugas yang di-assign ke user.
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Tugas yang dibuat oleh user.
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    /**
     * Aktivitas yang dilakukan user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
