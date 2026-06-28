<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        $tugas = [
            'Membuat ERD dan Normalisasi Database',
            'Desain Wireframe dan Prototype UI',
            'Setup Project Laravel dan Konfigurasi',
            'Implementasi Authentication',
            'Membuat API Endpoint CRUD',
            'Testing dan Debugging Aplikasi',
            'Dokumentasi Teknis',
            'Presentasi Hasil Akhir',
        ];

        return [
            'project_id' => Project::factory(),
            'judul' => fake()->unique()->randomElement($tugas),
            'deskripsi' => fake()->paragraph(),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'status' => fake()->randomElement(['belum_dimulai', 'sedang_dikerjakan', 'menunggu_review', 'selesai']),
            'deadline' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}
