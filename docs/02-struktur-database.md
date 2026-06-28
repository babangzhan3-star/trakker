# 🗄️ Struktur Database — TaskFlow

> Dokumen ini berisi desain database lengkap: daftar tabel, kolom, tipe data, relasi, dan penjelasan.
> Digunakan sebagai acuan untuk membuat migration Laravel.

---

## Entity Relationship Diagram (Deskripsi Teks)

```
┌──────────┐       ┌──────────────┐       ┌──────────┐
│  users   │       │   courses    │       │lecturers │
│          │       │  (mata_kuliah)│       │  (dosen) │
│  id (PK) │       │              │       │          │
│  name    │       │  id (PK)     │       │  id (PK) │
│  email   │       │  kode_mk     │       │  nama    │
│  nim     │       │  nama_mk     │       │  gelar   │
│  ...     │       │  sks         │       │  nidn    │
└────┬─────┘       └──────┬───────┘       │  email   │
     │                    │               └────┬─────┘
     │                    │                    │
     │    ┌───────────────┼────────────────────┘
     │    │               │
     │    │    ┌──────────┴──────────┐
     │    │    │     projects        │
     │    │    │                     │
     │    ├────┤ created_by (FK)     │
     │    │    │ course_id (FK)      │
     │    │    │ lecturer_id (FK)    │
     │    │    │ nama_proyek         │
     │    │    │ deadline            │
     │    │    │ status              │
     │    │    └────┬───────┬────────┘
     │    │         │       │
     │    │         │       └──────────────────┐
     │    │         │                          │
     │    │    ┌────┴──────────┐    ┌──────────┴──────────┐
     │    │    │  tasks        │    │  project_members    │
     │    │    │               │    │                     │
     │    │    │ id (PK)       │    │ id (PK)             │
     │    │    │ project_id(FK)│    │ project_id (FK)     │
     │    ├────┤ assigned_to   │    │ user_id (FK)        │
     │    │    │ created_by(FK)│    │ role                │
     │    │    │ judul         │    │ kontribusi_persen   │
     │    │    │ status        │    └─────────────────────┘
     │    │    │ deadline      │
     │    │    └───────────────┘
     │    │
     │    │    ┌──────────────────────┐
     │    │    │  activities          │
     │    │    │                      │
     │    ├────┤ user_id (FK)         │
     │    │    │ project_id (FK, null)│
     │    │    │ task_id (FK, null)   │
     │    │    │ tipe                 │
     │    │    │ deskripsi            │
     │    │    └──────────────────────┘
     │    │
```

---

## Relasi Antar Tabel

| Tabel A | Relasi | Tabel B | Foreign Key | Keterangan |
|---|---|---|---|---|
| `users` | 1:N | `projects` | `projects.created_by` | User yang membuat proyek (otomatis jadi ketua) |
| `users` | 1:N | `tasks` | `tasks.assigned_to` | User yang ditugaskan mengerjakan task |
| `users` | 1:N | `tasks` | `tasks.created_by` | User yang membuat task |
| `users` | 1:N | `activities` | `activities.user_id` | User yang melakukan aksi |
| `users` | N:M | `projects` | `project_members` | Anggota kelompok (via pivot) |
| `courses` | 1:N | `projects` | `projects.course_id` | Setiap proyek terkait satu mata kuliah |
| `lecturers` | 1:N | `projects` | `projects.lecturer_id` | Dosen pengampu proyek |
| `projects` | 1:N | `tasks` | `tasks.project_id` | Tugas dalam proyek |
| `projects` | 1:N | `project_members` | `project_members.project_id` | Anggota proyek |
| `projects` | 1:N | `activities` | `activities.project_id` | Aktivitas terkait proyek |
| `tasks` | 1:N | `activities` | `activities.task_id` | Aktivitas terkait task |

---

## Detail Tabel

### 1. Tabel `users` — Data Pengguna

**Fungsi:** Menyimpan data akun mahasiswa yang menggunakan aplikasi.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik pengguna |
| `name` | VARCHAR(100) | NOT NULL | Nama lengkap mahasiswa |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Email institusi (untuk login) |
| `nim` | VARCHAR(20) | NULLABLE, UNIQUE | Nomor Induk Mahasiswa (opsional) |
| `semester` | TINYINT | NULLABLE | Semester saat ini (1-14) |
| `kelas` | VARCHAR(5) | NULLABLE | Kelas (A, B, C, dst) |
| `avatar` | VARCHAR(255) | NULLABLE | Path foto profil |
| `password` | VARCHAR(255) | NOT NULL | Password terenkripsi (bcrypt) |
| `remember_token` | VARCHAR(100) | NULLABLE | Token "ingat saya" |
| `created_at` | TIMESTAMP | NULLABLE | Waktu pendaftaran |
| `updated_at` | TIMESTAMP | NULLABLE | Waktu update terakhir |

---

### 2. Tabel `courses` — Mata Kuliah

**Fungsi:** Menyimpan data mata kuliah yang diambil mahasiswa. Proyek tugas kelompok selalu terkait dengan satu mata kuliah.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik mata kuliah |
| `kode_mk` | VARCHAR(10) | UNIQUE, NOT NULL | Kode mata kuliah, e.g. "IF4301" |
| `nama_mk` | VARCHAR(100) | NOT NULL | Nama mata kuliah, e.g. "Pemrograman Web Lanjut" |
| `sks` | TINYINT | NULLABLE | Jumlah SKS (1-6) |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 3. Tabel `lecturers` — Dosen

**Fungsi:** Menyimpan data dosen pengampu mata kuliah. Satu dosen bisa mengampu beberapa proyek dari mata kuliah yang sama atau berbeda.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik dosen |
| `nama` | VARCHAR(100) | NOT NULL | Nama lengkap dosen (dengan gelar opsional) |
| `gelar` | VARCHAR(30) | NULLABLE | Gelar, e.g. "M.Kom", "Ph.D" |
| `nidn` | VARCHAR(20) | NULLABLE, UNIQUE | Nomor Induk Dosen Nasional |
| `email` | VARCHAR(100) | NULLABLE | Email dosen |
| `no_telp` | VARCHAR(15) | NULLABLE | Nomor telepon/WA |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

---

### 4. Tabel `projects` — Proyek/Kelompok

**Fungsi:** INTI APLIKASI. Setiap proyek mewakili **satu tugas kelompok** dari suatu mata kuliah. Proyek menyimpan informasi mata kuliah, dosen, ketua, dan deadline.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik proyek |
| `nama_proyek` | VARCHAR(150) | NOT NULL | Nama proyek, e.g. "Aplikasi TaskFlow" |
| `deskripsi` | TEXT | NULLABLE | Deskripsi singkat proyek |
| `course_id` | BIGINT UNSIGNED | FK → courses.id, NOT NULL | Mata kuliah terkait |
| `lecturer_id` | BIGINT UNSIGNED | FK → lecturers.id, NOT NULL | Dosen pengampu |
| `created_by` | BIGINT UNSIGNED | FK → users.id, NOT NULL | User yang membuat (sekaligus ketua) |
| `semester` | TINYINT | NULLABLE | Semester pengerjaan |
| `kelas` | VARCHAR(5) | NULLABLE | Kelas |
| `deadline` | DATE | NOT NULL | Tanggal deadline proyek |
| `status` | ENUM | NOT NULL, DEFAULT 'aktif' | Status proyek: `aktif`, `selesai`, `ditunda` |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Index:**
- `INDEX projects_course_id (course_id)`
- `INDEX projects_lecturer_id (lecturer_id)`
- `INDEX projects_created_by (created_by)`

---

### 5. Tabel `project_members` — Anggota Kelompok (Pivot)

**Fungsi:** Menghubungkan user dengan proyek. Mencatat peran (ketua/anggota) dan persentase kontribusi.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik |
| `project_id` | BIGINT UNSIGNED | FK → projects.id, NOT NULL | Proyek |
| `user_id` | BIGINT UNSIGNED | FK → users.id, NOT NULL | Anggota |
| `role` | ENUM | NOT NULL, DEFAULT 'anggota' | `ketua` atau `anggota` |
| `kontribusi_persen` | TINYINT | NOT NULL, DEFAULT 0 | Persentase kontribusi (0-100) |
| `created_at` | TIMESTAMP | NULLABLE | Waktu bergabung |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Constraint:**
- `UNIQUE project_user_unique (project_id, user_id)` — satu user hanya bisa jadi anggota sekali per proyek
- Satu proyek hanya boleh punya SATU ketua (validasi di level aplikasi)

---

### 6. Tabel `tasks` — Tugas

**Fungsi:** Menyimpan pembagian tugas individual dalam suatu proyek. Setiap tugas dikerjakan oleh satu anggota.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik tugas |
| `project_id` | BIGINT UNSIGNED | FK → projects.id, NOT NULL | Proyek terkait |
| `judul` | VARCHAR(200) | NOT NULL | Judul tugas |
| `deskripsi` | TEXT | NULLABLE | Detail/deskripsi tugas |
| `assigned_to` | BIGINT UNSIGNED | FK → users.id, NOT NULL | Penanggung jawab tugas |
| `created_by` | BIGINT UNSIGNED | FK → users.id, NOT NULL | Yang membuat tugas |
| `status` | ENUM | NOT NULL, DEFAULT 'belum_dimulai' | `belum_dimulai`, `sedang_dikerjakan`, `menunggu_review`, `selesai` |
| `deadline` | DATE | NOT NULL | Deadline tugas |
| `created_at` | TIMESTAMP | NULLABLE | — |
| `updated_at` | TIMESTAMP | NULLABLE | — |

**Index:**
- `INDEX tasks_project_id (project_id)`
- `INDEX tasks_assigned_to (assigned_to)`
- `INDEX tasks_status (status)`
- `INDEX tasks_deadline (deadline)`

---

### 7. Tabel `activities` — Riwayat Aktivitas

**Fungsi:** Mencatat log aktivitas untuk fitur timeline dan riwayat.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | ID unik |
| `user_id` | BIGINT UNSIGNED | FK → users.id, NOT NULL | Siapa yang melakukan |
| `project_id` | BIGINT UNSIGNED | FK → projects.id, NULLABLE | Proyek terkait (null jika bukan aktivitas proyek) |
| `task_id` | BIGINT UNSIGNED | FK → tasks.id, NULLABLE | Tugas terkait (null jika bukan aktivitas tugas) |
| `tipe` | ENUM | NOT NULL | Tipe aktivitas (lihat daftar di bawah) |
| `deskripsi` | VARCHAR(255) | NOT NULL | Deskripsi singkat aktivitas |
| `created_at` | TIMESTAMP | NULLABLE | Waktu aktivitas |

**Enum `tipe`:**
- `proyek_dibuat` — Proyek baru dibuat
- `proyek_diedit` — Detail proyek diubah
- `tugas_dibuat` — Tugas baru ditambahkan
- `tugas_diedit` — Detail tugas diubah
- `status_diubah` — Status tugas berubah
- `deadline_diubah` — Deadline tugas/proyek diubah
- `anggota_ditambahkan` — Anggota baru bergabung
- `anggota_dihapus` — Anggota dikeluarkan

**Index:**
- `INDEX activities_user_id (user_id)`
- `INDEX activities_project_id (project_id)`
- `INDEX activities_created_at (created_at)`

---

## Ringkasan SQL untuk Referensi

### Enum Values:

```sql
-- projects.status
ENUM('aktif', 'selesai', 'ditunda')

-- project_members.role
ENUM('ketua', 'anggota')

-- tasks.status
ENUM('belum_dimulai', 'sedang_dikerjakan', 'menunggu_review', 'selesai')

-- activities.tipe
ENUM('proyek_dibuat', 'proyek_diedit', 'tugas_dibuat', 'tugas_diedit',
     'status_diubah', 'deadline_diubah', 'anggota_ditambahkan', 'anggota_dihapus')
```

---

## Catatan Penting untuk Implementasi

1. **Soft Delete:** Tidak digunakan di versi awal. Hapus data langsung untuk kesederhanaan.
2. **Validasi di Aplikasi:**
   - Satu proyek hanya boleh punya 1 ketua (validasi saat insert/update `project_members`).
   - `assigned_to` di `tasks` harus merujuk ke user yang sudah jadi anggota proyek tersebut.
   - Deadline tugas tidak boleh melebihi deadline proyek (opsional, bisa di-skip dulu).
3. **Default Values:**
   - `tasks.status` default = `belum_dimulai`
   - `project_members.role` default = `anggota`
   - `project_members.kontribusi_persen` default = `0`
4. **Perhitungan Kontribusi:**
   - Dihitung otomatis: (jumlah tugas selesai user / total tugas proyek) × 100%
   - Diperbarui setiap kali status tugas berubah.
