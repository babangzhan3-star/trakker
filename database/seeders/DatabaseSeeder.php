<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── AKUN TEST ───────────────────────────────────────
        $fauzan = User::create([
            'name' => 'Fauzan Rahman',
            'email' => 'fauzan@student.edu',
            'nim' => '20210001',
            'semester' => 6,
            'kelas' => 'A',
            'password' => Hash::make('password'),
        ]);

        // ─── MATA KULIAH ────────────────────────────────────
        $mk1 = Course::create(['kode_mk' => 'IF4301', 'nama_mk' => 'Pemrograman Web Lanjut', 'sks' => 3]);
        $mk2 = Course::create(['kode_mk' => 'IF3202', 'nama_mk' => 'Algoritma dan Pemrograman', 'sks' => 4]);
        $mk3 = Course::create(['kode_mk' => 'IF4105', 'nama_mk' => 'Basis Data Lanjut', 'sks' => 3]);
        $mk4 = Course::create(['kode_mk' => 'IF4401', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3]);
        Course::create(['kode_mk' => 'IF3303', 'nama_mk' => 'Sistem Operasi', 'sks' => 3]);
        Course::create(['kode_mk' => 'IF4204', 'nama_mk' => 'Jaringan Komputer', 'sks' => 4]);
        Course::create(['kode_mk' => 'IF4502', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3]);
        Course::create(['kode_mk' => 'IF3101', 'nama_mk' => 'Statistika', 'sks' => 2]);

        // ─── DOSEN ──────────────────────────────────────────
        $d1 = Lecturer::create(['nama' => 'Rina Anggraini', 'gelar' => 'M.Kom', 'nidn' => '0012088401', 'email' => 'rina@kampus.ac.id', 'no_telp' => '081234567801']);
        $d2 = Lecturer::create(['nama' => 'Budi Santoso', 'gelar' => 'M.T.', 'nidn' => '0021057902', 'email' => 'budi@kampus.ac.id', 'no_telp' => '081234567802']);
        $d3 = Lecturer::create(['nama' => 'Doni Pratama', 'gelar' => 'Ph.D', 'nidn' => '0003128503', 'email' => 'doni@kampus.ac.id', 'no_telp' => '081234567803']);
        Lecturer::create(['nama' => 'Siti Nurhaliza', 'gelar' => 'M.Cs', 'nidn' => '0015068804', 'email' => 'siti@kampus.ac.id', 'no_telp' => '081234567804']);
        Lecturer::create(['nama' => 'Ahmad Fauzi', 'gelar' => 'Dr., M.Eng', 'nidn' => '0018098205', 'email' => 'ahmad@kampus.ac.id', 'no_telp' => '081234567805']);

        // ─── MAHASISWA LAIN ────────────────────────────────
        $rina = User::create(['name' => 'Rina Amelia', 'email' => 'rina@student.edu', 'nim' => '20210002', 'semester' => 6, 'kelas' => 'A', 'password' => Hash::make('password')]);
        $budiUser = User::create(['name' => 'Budi Hermawan', 'email' => 'budi@student.edu', 'nim' => '20210003', 'semester' => 6, 'kelas' => 'A', 'password' => Hash::make('password')]);
        $ani = User::create(['name' => 'Ani Lestari', 'email' => 'ani@student.edu', 'nim' => '20210004', 'semester' => 6, 'kelas' => 'B', 'password' => Hash::make('password')]);
        $dina = User::create(['name' => 'Dina Safitri', 'email' => 'dina@student.edu', 'nim' => '20210005', 'semester' => 6, 'kelas' => 'B', 'password' => Hash::make('password')]);
        $eko = User::create(['name' => 'Eko Prasetyo', 'email' => 'eko@student.edu', 'nim' => '20210006', 'semester' => 6, 'kelas' => 'A', 'password' => Hash::make('password')]);

        // ─── PROYEK 1: Pemrograman Web Lanjut ──────────────
        $proyek1 = Project::create([
            'nama_proyek' => 'Aplikasi TaskFlow',
            'deskripsi' => 'Membangun aplikasi manajemen tugas kelompok untuk mahasiswa berbasis Laravel dan MySQL.',
            'course_id' => $mk1->id,
            'lecturer_id' => $d1->id,
            'created_by' => $fauzan->id,
            'semester' => 6,
            'kelas' => 'A',
            'deadline' => '2026-06-20',
            'status' => 'aktif',
        ]);

        ProjectMember::create(['project_id' => $proyek1->id, 'user_id' => $fauzan->id, 'role' => 'ketua', 'kontribusi_persen' => 65]);
        ProjectMember::create(['project_id' => $proyek1->id, 'user_id' => $rina->id, 'role' => 'anggota', 'kontribusi_persen' => 80]);
        ProjectMember::create(['project_id' => $proyek1->id, 'user_id' => $budiUser->id, 'role' => 'anggota', 'kontribusi_persen' => 50]);
        ProjectMember::create(['project_id' => $proyek1->id, 'user_id' => $ani->id, 'role' => 'anggota', 'kontribusi_persen' => 90]);

        Task::create(['project_id' => $proyek1->id, 'judul' => 'Membuat ERD dan Normalisasi Database', 'assigned_to' => $fauzan->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-06-14']);
        Task::create(['project_id' => $proyek1->id, 'judul' => 'Desain Wireframe dan Prototype UI', 'assigned_to' => $rina->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-06-14']);
        Task::create(['project_id' => $proyek1->id, 'judul' => 'Setup Project Laravel', 'assigned_to' => $budiUser->id, 'created_by' => $fauzan->id, 'status' => 'sedang_dikerjakan', 'deadline' => '2026-06-16']);
        Task::create(['project_id' => $proyek1->id, 'judul' => 'Implementasi Authentication', 'assigned_to' => $budiUser->id, 'created_by' => $fauzan->id, 'status' => 'belum_dimulai', 'deadline' => '2026-06-18']);
        Task::create(['project_id' => $proyek1->id, 'judul' => 'Testing Aplikasi', 'assigned_to' => $ani->id, 'created_by' => $fauzan->id, 'status' => 'belum_dimulai', 'deadline' => '2026-06-20']);
        Task::create(['project_id' => $proyek1->id, 'judul' => 'Dokumentasi Proyek', 'assigned_to' => $rina->id, 'created_by' => $fauzan->id, 'status' => 'menunggu_review', 'deadline' => '2026-06-19']);

        // ─── PROYEK 2: Algoritma ───────────────────────────
        $proyek2 = Project::create([
            'nama_proyek' => 'Simulasi Algoritma Sorting',
            'deskripsi' => 'Membuat simulasi perbandingan algoritma sorting menggunakan Python dan visualisasi.',
            'course_id' => $mk2->id,
            'lecturer_id' => $d2->id,
            'created_by' => $rina->id,
            'semester' => 6,
            'kelas' => 'A',
            'deadline' => '2026-06-28',
            'status' => 'aktif',
        ]);

        ProjectMember::create(['project_id' => $proyek2->id, 'user_id' => $rina->id, 'role' => 'ketua', 'kontribusi_persen' => 70]);
        ProjectMember::create(['project_id' => $proyek2->id, 'user_id' => $fauzan->id, 'role' => 'anggota', 'kontribusi_persen' => 40]);
        ProjectMember::create(['project_id' => $proyek2->id, 'user_id' => $dina->id, 'role' => 'anggota', 'kontribusi_persen' => 60]);

        Task::create(['project_id' => $proyek2->id, 'judul' => 'Membuat slide presentasi', 'assigned_to' => $rina->id, 'created_by' => $rina->id, 'status' => 'selesai', 'deadline' => '2026-06-12']);
        Task::create(['project_id' => $proyek2->id, 'judul' => 'Implementasi Bubble Sort', 'assigned_to' => $fauzan->id, 'created_by' => $rina->id, 'status' => 'sedang_dikerjakan', 'deadline' => '2026-06-20']);
        Task::create(['project_id' => $proyek2->id, 'judul' => 'Implementasi Quick Sort', 'assigned_to' => $dina->id, 'created_by' => $rina->id, 'status' => 'belum_dimulai', 'deadline' => '2026-06-25']);
        Task::create(['project_id' => $proyek2->id, 'judul' => 'Visualisasi grafik', 'assigned_to' => $fauzan->id, 'created_by' => $rina->id, 'status' => 'belum_dimulai', 'deadline' => '2026-06-28']);

        // ─── PROYEK 3: Basis Data ──────────────────────────
        $proyek3 = Project::create([
            'nama_proyek' => 'Sistem Informasi Perpustakaan',
            'deskripsi' => 'Merancang dan mengimplementasikan database untuk sistem informasi perpustakaan kampus.',
            'course_id' => $mk3->id,
            'lecturer_id' => $d3->id,
            'created_by' => $budiUser->id,
            'semester' => 6,
            'kelas' => 'B',
            'deadline' => '2026-06-30',
            'status' => 'aktif',
        ]);

        ProjectMember::create(['project_id' => $proyek3->id, 'user_id' => $budiUser->id, 'role' => 'ketua', 'kontribusi_persen' => 55]);
        ProjectMember::create(['project_id' => $proyek3->id, 'user_id' => $ani->id, 'role' => 'anggota', 'kontribusi_persen' => 75]);
        ProjectMember::create(['project_id' => $proyek3->id, 'user_id' => $eko->id, 'role' => 'anggota', 'kontribusi_persen' => 30]);

        Task::create(['project_id' => $proyek3->id, 'judul' => 'Analisis kebutuhan', 'assigned_to' => $budiUser->id, 'created_by' => $budiUser->id, 'status' => 'selesai', 'deadline' => '2026-06-10']);
        Task::create(['project_id' => $proyek3->id, 'judul' => 'Desain ERD', 'assigned_to' => $budiUser->id, 'created_by' => $budiUser->id, 'status' => 'selesai', 'deadline' => '2026-06-13']);
        Task::create(['project_id' => $proyek3->id, 'judul' => 'Implementasi SQL', 'assigned_to' => $ani->id, 'created_by' => $budiUser->id, 'status' => 'sedang_dikerjakan', 'deadline' => '2026-06-22']);
        Task::create(['project_id' => $proyek3->id, 'judul' => 'Pengujian query', 'assigned_to' => $eko->id, 'created_by' => $budiUser->id, 'status' => 'belum_dimulai', 'deadline' => '2026-06-28']);

        // ─── PROYEK 4: RPL (Selesai) ──────────────────────
        $proyek4 = Project::create([
            'nama_proyek' => 'Aplikasi Pemesanan Tiket',
            'deskripsi' => 'Proyek RPL semester lalu: membangun aplikasi pemesanan tiket bioskop.',
            'course_id' => $mk4->id,
            'lecturer_id' => $d1->id,
            'created_by' => $fauzan->id,
            'semester' => 5,
            'kelas' => 'A',
            'deadline' => '2026-03-15',
            'status' => 'selesai',
        ]);

        ProjectMember::create(['project_id' => $proyek4->id, 'user_id' => $fauzan->id, 'role' => 'ketua', 'kontribusi_persen' => 100]);
        ProjectMember::create(['project_id' => $proyek4->id, 'user_id' => $rina->id, 'role' => 'anggota', 'kontribusi_persen' => 100]);
        ProjectMember::create(['project_id' => $proyek4->id, 'user_id' => $ani->id, 'role' => 'anggota', 'kontribusi_persen' => 100]);

        Task::create(['project_id' => $proyek4->id, 'judul' => 'Dokumen SRS', 'assigned_to' => $fauzan->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-03-01']);
        Task::create(['project_id' => $proyek4->id, 'judul' => 'Diagram UML', 'assigned_to' => $rina->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-03-05']);
        Task::create(['project_id' => $proyek4->id, 'judul' => 'Implementasi Frontend', 'assigned_to' => $ani->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-03-10']);
        Task::create(['project_id' => $proyek4->id, 'judul' => 'Laporan Akhir', 'assigned_to' => $fauzan->id, 'created_by' => $fauzan->id, 'status' => 'selesai', 'deadline' => '2026-03-15']);

        // ─── AKTIVITAS ────────────────────────────────────
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'proyek_dibuat', 'deskripsi' => 'membuat proyek "Aplikasi TaskFlow"', 'created_at' => now()->subDays(10)]);
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'anggota_ditambahkan', 'deskripsi' => 'menambahkan Rina Amelia sebagai anggota', 'created_at' => now()->subDays(9)]);
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'tugas_dibuat', 'deskripsi' => 'membuat tugas "Membuat ERD"', 'created_at' => now()->subDays(8)]);
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'tugas_dibuat', 'deskripsi' => 'membuat tugas "Desain Wireframe"', 'created_at' => now()->subDays(7)]);
        Activity::create(['user_id' => $rina->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'status_diubah', 'deskripsi' => 'menyelesaikan tugas "Desain Wireframe"', 'created_at' => now()->subDays(4)]);
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'status_diubah', 'deskripsi' => 'menyelesaikan tugas "Membuat ERD"', 'created_at' => now()->subDays(2)]);
        Activity::create(['user_id' => $budiUser->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'status_diubah', 'deskripsi' => 'memulai mengerjakan "Setup Project Laravel"', 'created_at' => now()->subHours(5)]);
        Activity::create(['user_id' => $rina->id, 'project_id' => $proyek2->id, 'task_id' => null, 'tipe' => 'proyek_dibuat', 'deskripsi' => 'membuat proyek "Simulasi Algoritma Sorting"', 'created_at' => now()->subDays(6)]);
        Activity::create(['user_id' => $budiUser->id, 'project_id' => $proyek3->id, 'task_id' => null, 'tipe' => 'proyek_dibuat', 'deskripsi' => 'membuat proyek "Sistem Informasi Perpustakaan"', 'created_at' => now()->subDays(5)]);
        Activity::create(['user_id' => $fauzan->id, 'project_id' => $proyek1->id, 'task_id' => null, 'tipe' => 'anggota_ditambahkan', 'deskripsi' => 'menambahkan Ani Lestari sebagai anggota', 'created_at' => now()->subHours(3)]);

        echo "✅ Seeder selesai!\n";
        echo "🔑 Login: fauzan@student.edu / password\n";
    }
}
