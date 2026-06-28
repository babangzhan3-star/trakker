<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $tipe = fake()->randomElement([
            'proyek_dibuat', 'proyek_diedit',
            'tugas_dibuat', 'tugas_diedit',
            'status_diubah', 'deadline_diubah',
            'anggota_ditambahkan', 'anggota_dihapus'
        ]);

        $deskripsi = match ($tipe) {
            'proyek_dibuat' => 'membuat proyek baru',
            'proyek_diedit' => 'mengubah detail proyek',
            'tugas_dibuat' => 'menambahkan tugas baru',
            'tugas_diedit' => 'mengubah detail tugas',
            'status_diubah' => 'mengubah status tugas menjadi ' . fake()->randomElement(['Selesai', 'Sedang Dikerjakan', 'Menunggu Review']),
            'deadline_diubah' => 'mengubah deadline',
            'anggota_ditambahkan' => 'menambahkan anggota ke proyek',
            'anggota_dihapus' => 'menghapus anggota dari proyek',
        };

        return [
            'user_id' => User::factory(),
            'project_id' => Project::factory(),
            'task_id' => null,
            'tipe' => $tipe,
            'deskripsi' => $deskripsi,
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
