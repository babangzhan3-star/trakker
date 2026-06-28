<?php

namespace Database\Factories;

use App\Models\Lecturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dosen = [
            ['Rina Anggraini', 'M.Kom', '0012088401', 'rina.anggraini@kampus.ac.id'],
            ['Budi Santoso', 'M.T.', '0021057902', 'budi.santoso@kampus.ac.id'],
            ['Doni Pratama', 'Ph.D', '0003128503', 'doni.pratama@kampus.ac.id'],
            ['Siti Nurhaliza', 'M.Cs', '0015068804', 'siti.nurhaliza@kampus.ac.id'],
            ['Ahmad Fauzi', 'Dr., M.Eng', '0018098205', 'ahmad.fauzi@kampus.ac.id'],
        ];

        $d = fake()->unique()->randomElement($dosen);

        return [
            'nama' => $d[0],
            'gelar' => $d[1],
            'nidn' => $d[2],
            'email' => $d[3],
            'no_telp' => '08' . fake()->numerify('##########'),
        ];
    }
}
