<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $namaProyek = [
            'Aplikasi Manajemen Tugas',
            'Sistem Informasi Perpustakaan',
            'Website E-Commerce Sederhana',
            'Aplikasi Pemesanan Makanan',
            'Sistem Pendaftaran Online',
            'Dashboard Monitoring Proyek',
        ];

        return [
            'nama_proyek' => fake()->randomElement($namaProyek),
            'deskripsi' => fake()->paragraph(2),
            'course_id' => Course::factory(),
            'lecturer_id' => Lecturer::factory(),
            'created_by' => User::factory(),
            'semester' => fake()->numberBetween(1, 8),
            'kelas' => fake()->randomElement(['A', 'B', 'C']),
            'deadline' => fake()->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'status' => fake()->randomElement(['aktif', 'aktif', 'aktif', 'selesai']),
        ];
    }
}
