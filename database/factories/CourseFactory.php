<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mataKuliah = [
            ['IF4301', 'Pemrograman Web Lanjut', 3],
            ['IF3202', 'Algoritma dan Pemrograman', 4],
            ['IF4105', 'Basis Data Lanjut', 3],
            ['IF4401', 'Rekayasa Perangkat Lunak', 3],
            ['IF3303', 'Sistem Operasi', 3],
            ['IF4204', 'Jaringan Komputer', 4],
            ['IF4502', 'Kecerdasan Buatan', 3],
            ['IF3101', 'Statistika', 2],
        ];

        $mk = fake()->unique()->randomElement($mataKuliah);

        return [
            'kode_mk' => $mk[0],
            'nama_mk' => $mk[1],
            'sks' => $mk[2],
        ];
    }
}
