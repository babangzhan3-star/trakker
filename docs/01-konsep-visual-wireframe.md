# 🎨 Konsep Visual & Wireframe — TaskFlow

> Dokumen ini berisi deskripsi UI/UX detail untuk setiap halaman aplikasi TaskFlow.
> Dibuat sebagai panduan implementasi Blade template.

---

## Prinsip Desain Global

| Elemen | Spesifikasi |
|---|---|
| **Font** | Inter (Google Fonts) — bersih, modern, readability tinggi |
| **Warna Aksen** | `#2563EB` (Biru Navy) sebagai primary; `#10B981` (Emerald) sebagai indikator sukses/positif |
| **Background** | `#F8FAFC` (Slate 50) untuk body; `#FFFFFF` untuk card/surface |
| **Border Radius** | `12px` (default card), `8px` (button/input), `full` (badge kecil) |
| **Shadow** | Sangat subtle: `0 1px 3px rgba(0,0,0,0.04)` — bukan shadow tebal |
| **Spacing** | Padding card: `24px`; gap antar section: `32px`; page padding: `32px` |
| **Icon Library** | Bootstrap Icons (bi) |
| **Breakpoint Mobile** | Di bawah `768px`, navbar jadi bottom navigation bar |

---

## Color & Typography Tokens

| Token | Value | Usage |
|---|---|---|
| `--primary` | `#2563EB` | Tombol utama, link, border focus, progress fill |
| `--primary-light` | `#EFF6FF` | Background item aktif, badge info |
| `--success` | `#10B981` | Badge selesai, indikator deadline aman |
| `--warning` | `#F59E0B` | Badge proses, indikator deadline dekat |
| `--danger` | `#EF4444` | Badge terlambat, tombol hapus, error |
| `--slate-50` | `#F8FAFC` | Body background |
| `--slate-100` | `#F1F5F9` | Tab container, hover background |
| `--slate-200` | `#E2E8F0` | Border card, divider |
| `--slate-500` | `#64748B` | Teks sekunder, placeholder |
| `--slate-700` | `#334155` | Label, teks body |
| `--slate-900` | `#0F172A` | Heading, teks utama |

| Typography | Size / Weight |
|---|---|
| Page Heading | 24px / 700 |
| Section Heading | 18px / 600 |
| Card Title | 16px / 600 |
| Body Text | 14px / 400 |
| Small / Caption | 12px / 400 |
| Button Text | 14px / 600 |

---

## 1. Landing Page (`/`)

**Tujuan:** Memperkenalkan TaskFlow ke pengunjung yang belum login, mendorong mereka register.

### Struktur Layout:

```
┌─────────────────────────────────────────────────────┐
│  [NAVBAR: TaskFlow logo | Fitur | Login | Register]  │
│  background: white, sticky top, border-bottom subtle │
├─────────────────────────────────────────────────────┤
│                                                       │
│  HERO SECTION (full viewport height - 80px navbar)   │
│  ┌─────────────────────┐ ┌────────────────────────┐  │
│  │                     │ │                        │  │
│  │  Judul besar:       │ │  Ilustrasi SVG         │  │
│  │  "Kelola Tugas      │ │  (laptop + checklist   │  │
│  │   Kelompok Tanpa    │ │   + orang kolaborasi)  │  │
│  │   Ribet"            │ │                        │  │
│  │                     │ │  Style: flat vector     │  │
│  │  Subjudul: "Task-   │ │  Warna: biru navy      │  │
│  │  Flow bantu kamu    │ │  + abu-abu soft        │  │
│  │  dan tim selesaikan │ │                        │  │
│  │  tugas kuliah lebih │ └────────────────────────┘  │
│  │  terorganisir."     │                              │
│  │                     │                              │
│  │  [Mulai Sekarang]   │                              │
│  │  (btn primary,      │                              │
│  │   rounded-full,     │                              │
│  │   px-6 py-3)        │                              │
│  └─────────────────────┘                              │
│                                                       │
├─────────────────────────────────────────────────────┤
│  FITUR UTAMA (3 kolom grid)                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐          │
│  │ 🗂️ icon  │  │ ✅ icon  │  │ 📊 icon  │          │
│  │ Manajemen│  │ Tracking │  │ Laporan  │          │
│  │ Proyek   │  │ Tugas    │  │ Progres  │          │
│  │          │  │          │  │          │          │
│  │ Deskripsi│  │ Deskripsi│  │ Deskripsi│          │
│  │ singkat  │  │ singkat  │  │ singkat  │          │
│  └──────────┘  └──────────┘  └──────────┘          │
│                                                       │
├─────────────────────────────────────────────────────┤
│  CTA SECTION: "Siap atur tugas kelompokmu?"          │
│  background: gradient subtle blue                    │
│  [Daftar Gratis — btn white]                         │
│                                                       │
├─────────────────────────────────────────────────────┤
│  FOOTER: © 2026 TaskFlow | Dibuat untuk tugas kuliah  │
└─────────────────────────────────────────────────────┘
```

### Detail Komponen:

- **Navbar:** Background putih solid, tinggi ~64px. Logo di kiri (teks "TaskFlow" dengan weight 700, warna navy). Link navigasi di kanan: "Fitur", "Login" (outline button), "Register" (filled primary). Saat mobile, link jadi hamburger menu.
- **Hero:** Dua kolom (kiri teks, kanan ilustrasi). Judul pakai font size 48px, weight 800, warna `#0F172A` (slate 900). Subjudul 18px, warna `#64748B` (slate 500). CTA button warna `#2563EB`, text putih, hover jadi `#1D4ED8`.
- **Fitur Section:** Judul section "Kenapa TaskFlow?" di tengah. Tiga card tanpa shadow tebal — hanya border `1px solid #E2E8F0`, background putih, padding 32px, border-radius 12px. Icon di atas (font-size 32px), judul fitur (18px, weight 600), deskripsi (14px, slate 500).
- **CTA Section:** Background `linear-gradient(135deg, #2563EB, #3B82F6)`, padding 64px vertikal, teks putih.
- **Footer:** Sederhana, background `#1E293B`, teks `#94A3B8`, height ~48px, centered.

---

## 2. Halaman Login (`/login`)

**Tujuan:** Autentikasi pengguna terdaftar.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────┐
│  Layout: Fullscreen center (tidak ada navbar)         │
│                                                        │
│           ┌──────────────────────┐                    │
│           │   🔷 TaskFlow        │  (logo + nama)     │
│           │                      │                    │
│           │   Selamat Datang     │  (heading h2)      │
│           │   Kembali            │                    │
│           │                      │                    │
│           │   Masuk ke akunmu    │  (subtitle)        │
│           │                      │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  📧 Email      │ │  (input, border    │
│           │   └────────────────┘ │   rounded-lg,      │
│           │                      │   focus:ring blue) │
│           │   ┌────────────────┐ │                    │
│           │   │  🔒 Password   │ │                    │
│           │   └────────────────┘ │                    │
│           │                      │                    │
│           │   [✓] Ingat Saya     │  (checkbox)        │
│           │                      │                    │
│           │   [══════ Masuk ═══]│  (btn full width)   │
│           │                      │                    │
│           │   ───────────────    │                    │
│           │   Belum punya akun?  │                    │
│           │   [Daftar →]         │  (link text)       │
│           └──────────────────────┘                    │
│                                                        │
│  Background: slate-50 dengan pattern dot subtle        │
└──────────────────────────────────────────────────────┘
```

### Detail Komponen:

- **Layout:** Centered card, max-width `440px`, tidak ada navbar atau footer. Background body `#F8FAFC` dengan subtle dot pattern.
- **Card:** Background putih, padding `40px`, border-radius `16px`, shadow sangat subtle.
- **Logo:** Di atas card, ikon kecil + teks "TaskFlow", warna navy, font-weight 700, size 24px.
- **Heading:** "Selamat Datang Kembali" — 24px, weight 700, slate 900. Subtitle "Masuk ke akunmu" — 14px, slate 500.
- **Input:** Label di atas input. Border `1px solid #CBD5E1`, rounded `8px`, padding `10px 14px`. Saat focus: border jadi `#2563EB`, ring `0 0 0 3px rgba(37,99,235,0.1)`.
- **Button:** Full width, background `#2563EB`, text putih, padding `12px`, rounded `8px`, weight 600.
- **Divider:** Teks "atau" di tengah garis horizontal tipis.
- **Link register:** Teks kecil di bawah, "Belum punya akun?" + link "Daftar" warna primary.

---

## 3. Halaman Register (`/register`)

**Tujuan:** Pendaftaran akun baru.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────┐
│  Layout: Mirip Login, card lebih tinggi               │
│                                                        │
│           ┌──────────────────────┐                    │
│           │   🔷 TaskFlow        │                    │
│           │                      │                    │
│           │   Buat Akun Baru     │                    │
│           │                      │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  👤 Nama       │ │                    │
│           │   └────────────────┘ │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  📧 Email      │ │                    │
│           │   └────────────────┘ │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  🎓 NIM        │ │  (opsional)        │
│           │   └────────────────┘ │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  🔒 Password   │ │                    │
│           │   └────────────────┘ │                    │
│           │   ┌────────────────┐ │                    │
│           │   │  🔒 Konfirmasi │ │                    │
│           │   └────────────────┘ │                    │
│           │                      │                    │
│           │   [════ Daftar ════]│                    │
│           │                      │                    │
│           │   Sudah punya akun?  │                    │
│           │   [Masuk →]          │                    │
│           └──────────────────────┘                    │
└──────────────────────────────────────────────────────┘
```

### Detail Komponen:

- Mirip login, hanya lebih banyak field. NIM field opsional (ditandai dengan teks kecil "opsional" di samping label).
- Indikator kekuatan password (opsional, strip 3 segment warna abu/hijau).

---

## 4. Dashboard (`/dashboard`)

**Tujuan:** Halaman utama setelah login — pusat informasi semua aktivitas.

### Struktur Layout:

```
┌─────────────────────────────────────────────────────────────┐
│  [SIDEBAR kiri: 240px]  │  MAIN CONTENT (flex-1)            │
│                          │                                    │
│  🔷 TaskFlow             │  Selamat pagi, Fauzan! 👋         │
│  ──────────────────────  │  Semester 6 • 3 Proyek Aktif      │
│                          │                                    │
│  📊 Dashboard (active)   │  ┌─────────────────────────────┐  │
│  📁 Proyek Saya          │  │  QUICK STATS (horizontal)   │  │
│  ✅ Tugas Saya           │  │  ┌──────┐┌──────┐┌──────┐  │  │
│  👥 Anggota              │  │  │3     ││8     ││75%   │  │  │
│  📚 Mata Kuliah          │  │  │Proyek││Tugas ││Selesai│  │  │
│  👨‍🏫 Dosen               │  │  │Aktif ││Aktif ││dari   │  │  │
│  📈 Laporan              │  │  │      ││      ││total  │  │  │
│  ──────────────────────  │  │  └──────┘└──────┘└──────┘  │  │
│                          │  └─────────────────────────────┘  │
│  ⚙️ Pengaturan           │                                    │
│                          │  ┌──────────────────────────────┐ │
│  👤 Fauzan               │  │  SECTION KIRI (60%)          │ │
│  fauzan@student.edu      │  │                              │ │
│                          │  │  🔥 TUGAS MINGGU INI         │ │
│                          │  │  ┌────────────────────────┐  │ │
│                          │  │  │ ⬤ Buat ERD     2 hr lg │  │ │
│                          │  │  │ ⬤ Wireframe UI  3 hr lg│  │ │
│                          │  │  │ ◉ Setup Laravel  besok │  │ │
│                          │  │  │ ◎ Presentasi    lwt 2hr│  │ │
│                          │  │  └────────────────────────┘  │ │
│                          │  │                              │ │
│                          │  │  📋 PROYEK TERBARU           │ │
│                          │  │  (2 card horizontal, dgn     │ │
│                          │  │   progress bar kecil)        │ │
│                          │  └──────────────────────────────┘ │
│                          │                                    │
│                          │  ┌──────────────────────────────┐ │
│                          │  │  SECTION KANAN (40%)         │ │
│                          │  │                              │ │
│                          │  │  🕐 AKTIVITAS TERBARU        │ │
│                          │  │  ┌────────────────────────┐  │ │
│                          │  │  │ ● Fauzan buat tugas    │  │ │
│                          │  │  │   "Buat ERD" · 5m lalu │  │ │
│                          │  │  │ ● Rina selesai tugas   │  │ │
│                          │  │  │   "Dokumentasi"· 1j lalu│  │ │
│                          │  │  │ ● Budi gabung proyek   │  │ │
│                          │  │  │   "TaskFlow" · 3j lalu │  │ │
│                          │  │  └────────────────────────┘  │ │
│                          │  │                              │ │
│                          │  │  📊 DISTRIBUSI TUGAS         │ │
│                          │  │  (bar horizontal kecil)      │ │
│                          │  │  Selesai: 12 | Proses: 4     │ │
│                          │  │  Review: 2  | Belum: 6       │ │
│                          │  └──────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Sidebar:**
- Lebar `240px`, background putih, border kanan `1px solid #E2E8F0`.
- Logo di atas: teks "TaskFlow" + icon, padding `20px 24px`.
- Menu item: padding `10px 24px`, border-radius `8px` (margin `8px`). Item aktif: background `#EFF6FF`, teks `#2563EB`, weight 600, indikator bulat kecil di kiri.
- Profil user di bagian bawah: foto avatar bulat (40px), nama, dan email.

**Top Bar:**
- Teks sapaan: "Selamat pagi, Fauzan! 👋" — 24px, weight 700.
- Subtitle: "Semester 6 • 3 Proyek Aktif" — 14px, slate 500.

**Quick Stats:**
- 3 mini-stat dalam satu row horizontal, dipisahkan divider vertikal tipis.
- Angka besar (24px, weight 700, slate 900), label kecil (12px, slate 500, uppercase).

**Section Kiri - Tugas Minggu Ini:**
- Card putih, padding `20px`, border-radius `12px`.
- Judul: "🔥 Tugas Minggu Ini" — 16px, weight 600, dengan badge jumlah.
- List tugas: indikator warna bulat (8px) + nama tugas + deadline relatif di kanan.
- Indikator: hijau (>3 hari), kuning (1-3 hari), merah (terlambat).
- Max 5 tugas, link "Lihat semua →".

**Section Kiri - Proyek Terbaru:**
- 2-3 card horizontal mini: nama proyek + mata kuliah + progress bar kecil (height 6px).

**Section Kanan - Aktivitas Terbaru:**
- Timeline style: garis vertikal tipis di kiri, dot bulat di setiap item.
- Setiap item: icon aktivitas + teks + waktu relatif. Max 5 item.

**Section Kanan - Distribusi Tugas:**
- Bar horizontal kecil dengan 4 segmen warna.
- Legend: ⬤ Selesai ⬤ Proses ⬤ Review ⬤ Belum.

**Responsive (Mobile < 768px):**
- Sidebar hilang → bottom navigation bar (fixed) dengan 4-5 icon.
- Section kiri-kanan stacked vertikal.

---

## 5. Daftar Proyek (`/projects`)

**Tujuan:** Melihat dan mengelola semua proyek/kelompok.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  📁 Proyek Saya          [+ Proyek Baru]        │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  [🔍 Cari proyek...]  [Filter: Semua ▾]   │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  GRID 2 KOLOM (desktop) / 1 KOLOM (mobile)      │
│             │                                                  │
│             │  ┌──────────────────┐ ┌──────────────────┐     │
│             │  │ 🟢 Aktif         │ │ 🟡 Menunggu       │     │
│             │  │                  │ │   Review          │     │
│             │  │ Pemrograman Web  │ │ Algoritma         │     │
│             │  │ Lanjut           │ │ Pemrograman       │     │
│             │  │                  │ │                   │     │
│             │  │ Dosen: Bu Rina   │ │ Dosen: Pak Budi   │     │
│             │  │                  │ │                   │     │
│             │  │ 👥 4 Anggota     │ │ 👥 3 Anggota      │     │
│             │  │                  │ │                   │     │
│             │  │ ████████░░ 75%   │ │ █████░░░░░ 45%   │     │
│             │  │ 8/12 tugas       │ │ 5/11 tugas        │     │
│             │  │  selesai         │ │  selesai          │     │
│             │  │                  │ │                   │     │
│             │  │ Deadline:        │ │ Deadline:         │     │
│             │  │ 20 Juni 2026     │ │ 28 Juni 2026      │     │
│             │  │                  │ │                   │     │
│             │  │ [Lihat Detail →] │ │ [Lihat Detail →]  │     │
│             │  └──────────────────┘ └──────────────────┘     │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Project Card:**
- Background putih, border `1px solid #E2E8F0`, border-radius `14px`, padding `24px`.
- Indikator status di pojok kanan atas: badge kecil (🟢 Aktif, 🟡 Review, 🔴 Deadline).
- Nama mata kuliah: 18px, weight 600, slate 800.
- Nama dosen: 14px, slate 500, dengan icon `bi-person`.
- Jumlah anggota: 14px, slate 500, dengan icon `bi-people`.
- **Progress Bar:** Height `8px`, background `#E2E8F0`, border-radius full. Fill warna hijau (>70%), kuning (40-70%), merah (<40%).
- Deadline dengan icon kalender, warna menyesuaikan.
- Link "Lihat Detail →": teks primary, weight 500, 14px.
- **Hover:** card naik translateY -2px, shadow bertambah subtle.

**Empty State:** Ilustrasi + "Belum ada proyek" + tombol "Buat Proyek Pertama".

---

## 6. Detail Proyek (`/projects/{id}`)

**Tujuan:** Melihat detail satu proyek, daftar tugas, anggota, dan progress.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  ← Kembali  (breadcrumb)                         │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  HEADER PROYEK                              │ │
│             │  │                                              │ │
│             │  │  Pemrograman Web Lanjut     🟢 Aktif        │ │
│             │  │  Dosen: Bu Rina, M.Kom                      │ │
│             │  │  Semester 6 • Kelas A                       │ │
│             │  │  Deadline: 20 Juni 2026                     │ │
│             │  │                                              │ │
│             │  │  [Edit Proyek]  [Tambah Tugas]  [⚙️]       │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  ┌────────────────────┐ ┌───────────────────┐  │
│             │  │  📊 PROGRESS       │ │  👥 ANGGOTA (4)    │  │
│             │  │                    │ │                    │  │
│             │  │  ████████░░ 75%    │ │  👑 Fauzan (Ketua) │  │
│             │  │  9/12 tugas selesai│ │     65% kontribusi  │  │
│             │  │                    │ │  👤 Rina (Anggota)  │  │
│             │  │  ⬤ Selesai: 9     │ │     80% kontribusi  │  │
│             │  │  ⬤ Proses: 2      │ │  👤 Budi (Anggota)  │  │
│             │  │  ⬤ Review: 1      │ │     50% kontribusi  │  │
│             │  │  ⬤ Belum: 0       │ │  👤 Ani (Anggota)   │  │
│             │  │                    │ │     90% kontribusi  │  │
│             │  │                    │ │                    │  │
│             │  │                    │ │  [+ Tambah Anggota] │  │
│             │  └────────────────────┘ └───────────────────┘  │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  ✅ DAFTAR TUGAS                            │ │
│             │  │                                              │ │
│             │  │  [🔍 Cari]  [Filter Status ▾]  [Urut ▾]   │ │
│             │  │                                              │ │
│             │  │  TABEL MODERN (border-collapse: separate)   │ │
│             │  │  Tugas | PIC | Status | Deadline | Aksi     │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  🕐 AKTIVITAS PROYEK (5 item terakhir)      │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Breadcrumb:** "← Kembali ke Daftar Proyek" — link text dengan icon panah, warna slate 500.

**Header Proyek:** Card putih, padding `24px`, border-radius `12px`, border `1px solid #E2E8F0`.

**Progress Card (kiri, 55%):** Progress bar besar (height 12px), ringkasan status dengan 4 legend.

**Anggota Card (kanan, 45%):** List anggota dengan avatar kecil (inisial dalam lingkaran), mahkota (👑) untuk ketua, persentase kontribusi.

**Tabel Tugas:**
- Style: border-collapse separate, border-spacing 0, setiap row border-bottom subtle.
- Header: background `#F8FAFC`, text uppercase 11px, slate 500, weight 600.
- Row: hover background `#F8FAFC`, padding vertikal `12px`.
- **Badge Status:**
  - Belum Dimulai: background `#F1F5F9`, teks `#64748B`, dot abu-abu.
  - Sedang Dikerjakan: background `#FEF3C7`, teks `#D97706`, dot kuning.
  - Menunggu Review: background `#DBEAFE`, teks `#2563EB`, dot biru.
  - Selesai: background `#D1FAE5`, teks `#059669`, dot hijau.

---

## 7. Tambah/Edit Proyek (`/projects/create` & `/projects/{id}/edit`)

**Tujuan:** Form untuk membuat atau mengedit proyek.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  ← Kembali                                      │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  FORM (max-width: 600px)                    │ │
│             │  │                                              │ │
│             │  │  Nama Proyek *                               │ │
│             │  │  ┌──────────────────────────────────────┐   │ │
│             │  │  │ e.g. "Aplikasi TaskFlow"             │   │ │
│             │  │  └──────────────────────────────────────┘   │ │
│             │  │                                              │ │
│             │  │  Mata Kuliah *      [Pilih ▾]               │ │
│             │  │  [+ Tambah Mata Kuliah Baru]                │ │
│             │  │                                              │ │
│             │  │  Dosen *             [Pilih ▾]              │ │
│             │  │  [+ Tambah Dosen Baru]                      │ │
│             │  │                                              │ │
│             │  │  Semester            [6 ▾]                  │ │
│             │  │  Kelas (opsional)    [A ▾]                  │ │
│             │  │                                              │ │
│             │  │  Deadline *          [📅 dd/mm/yyyy]        │ │
│             │  │                                              │ │
│             │  │  Deskripsi (opsional)                        │ │
│             │  │  ┌──────────────────────────────────────┐   │ │
│             │  │  │ Deskripsi singkat proyek...          │   │ │
│             │  │  └──────────────────────────────────────┘   │ │
│             │  │                                              │ │
│             │  │  [Batal]              [💾 Simpan Proyek]    │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

- **Form Container:** Max-width `600px`, tidak full-width (terinspirasi Linear).
- Label di atas input, 14px, weight 500, slate 700. Tanda * untuk field wajib.
- Input text: height `42px`, border `1px solid #CBD5E1`, rounded `8px`, padding `10px 14px`.
- Link "+ Tambah Mata Kuliah Baru" dan "+ Tambah Dosen Baru" membuka modal kecil.
- Tombol Batal (outline) dan Simpan (primary, full width).
- Error message: teks merah kecil (12px) di bawah input.

---

## 8. Daftar Semua Tugas (`/tasks`)

**Tujuan:** Melihat semua tugas dari semua proyek dalam satu tempat.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  ✅ Semua Tugas            [+ Tugas Baru]       │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  TAB NAVIGASI (pill-style)                  │ │
│             │  │  [Semua] [Belum] [Proses] [Review] [Selesai]│ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  [🔍 Cari]  [Proyek: Semua ▾]  [Urut ▾]  │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  LIST VIEW (bukan tabel polos)                   │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │ ⬤  Buat ERD                          🟡   │ │
│             │  │    Pemrograman Web Lanjut · PIC: Fauzan    │ │
│             │  │    📅 Deadline: 15 Jun (2 hari lagi)       │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │ ⬤  Wireframe UI                      🟢   │ │
│             │  │    Pemrograman Web Lanjut · PIC: Rina      │ │
│             │  │    📅 Deadline: 20 Jun (7 hari lagi)       │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Tab Navigasi:**
- Pill container. Background `#F1F5F9`, padding `4px`, border-radius `10px`.
- Item aktif: background putih, shadow subtle, text slate 900.
- Item non-aktif: text slate 500, hover slate 700.

**Task List Item (Card Style):**
- Setiap item: background putih, border `1px solid #E2E8F0`, border-radius `12px`, padding `16px 20px`, margin-bottom `8px`.
- Baris 1: Indikator status (dot 10px) + Nama tugas (16px, weight 600) + Badge di kanan.
- Baris 2: Proyek terkait · PIC (avatar kecil + nama). 13px, slate 500.
- Baris 3: Icon kalender + Deadline + waktu relatif.
- Hover: background `#F8FAFC`, cursor pointer.

---

## 9. Tambah/Edit Tugas (`/tasks/create` & `/tasks/{id}/edit`)

**Tujuan:** Form CRUD tugas.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  ← Kembali                                      │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  FORM (max-width: 600px)                    │ │
│             │  │                                              │ │
│             │  │  Judul Tugas *                               │ │
│             │  │  Proyek *            [Pilih ▾]              │ │
│             │  │  Penanggung Jawab *  [Pilih ▾]              │ │
│             │  │                                              │ │
│             │  │  Status (custom radio pill)                  │ │
│             │  │  ○ Belum  ● Proses  ○ Review  ○ Selesai     │ │
│             │  │                                              │ │
│             │  │  Deadline *          [📅 dd/mm/yyyy]        │ │
│             │  │  Deskripsi           [textarea]             │ │
│             │  │                                              │ │
│             │  │  [Batal]          [💾 Simpan Tugas]         │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Radio Status — Custom Pill Style:**
- Bukan radio native. Tiap opsi berupa "pill" dengan ikon dan teks dalam satu row.
- Opsi aktif: background biru muda, border biru, teks biru.
- Opsi non-aktif: background putih, border abu, teks abu.

**Select PIC (Penanggung Jawab):**
- Difilter berdasarkan proyek yang dipilih. Jika proyek belum dipilih → disabled "Pilih proyek terlebih dahulu".

---

## 10. Manajemen Anggota (`/projects/{id}/members`)

**Tujuan:** Mengelola anggota dalam satu proyek.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  👥 Anggota — Pemrograman Web Lanjut            │
│             │                                                  │
│             │  ┌──────────────────────┐ ┌──────────────────┐ │
│             │  │  Ketua Kelompok       │ │  Anggota (3)     │ │
│             │  │  ┌────────────────┐   │ │ ┌──────────────┐ │ │
│             │  │  │ 👤 Fauzan      │   │ │ │ 👤 Rina      │ │ │
│             │  │  │ Kontrib: 65%   │   │ │ │ Kontrib: 80% │ │ │
│             │  │  │ Tugas: 3/4     │   │ │ │ Tugas: 4/4   │ │ │
│             │  │  └────────────────┘   │ │ └──────────────┘ │ │
│             │  └──────────────────────┘ └──────────────────┘ │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  ➕ Tambah Anggota                          │ │
│             │  │  [Cari user...] [Cari]                     │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

**Ketua Card:** Background `#F0F9FF`, border biru subtle, badge "👑 Ketua".

**Member Card:** Avatar bulat (48px), nama, email, progress bar kontribusi, tombol dropdown `[⋯]`.

---

## 11. Mata Kuliah (`/courses`)

**Tujuan:** CRUD data mata kuliah. Modal-based (bukan halaman terpisah).

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  📚 Mata Kuliah      [+ Tambah Mata Kuliah]     │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  [🔍 Cari mata kuliah...]                  │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  TABEL MODERN                                   │
│             │  Kode MK | Nama | SKS | Proyek | Aksi           │
│             │                                                  │
│             │  (klik row → modal edit, bukan pindah halaman)  │
└──────────────────────────────────────────────────────────────┘
```

---

## 12. Dosen (`/lecturers`)

**Tujuan:** CRUD data dosen. Card grid (bukan tabel).

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  👨‍🏫 Dosen              [+ Tambah Dosen]         │
│             │                                                  │
│             │  GRID 3 KOLOM (card)                            │
│             │                                                  │
│             │  ┌──────────┐ ┌──────────┐ ┌──────────┐       │
│             │  │ 👤       │ │ 👤       │ │ 👤       │       │
│             │  │ Bu Rina  │ │ Pak Budi │ │ Pak Doni │       │
│             │  │ M.Kom    │ │ M.T.     │ │ Ph.D     │       │
│             │  │ 📧 rina@ │ │ 📧 budi@ │ │ 📧 doni@ │       │
│             │  │ 2 Proyek │ │ 1 Proyek │ │ 0 Proyek │       │
│             │  │ [⋯]      │ │ [⋯]      │ │ [⋯]      │       │
│             │  └──────────┘ └──────────┘ └──────────┘       │
└──────────────────────────────────────────────────────────────┘
```

### Detail Komponen:

- Avatar besar di tengah (inisial, background warna random pastel).
- Nama (16px, weight 600), gelar di bawah (13px, slate 500).
- Badge jumlah proyek: "2 Proyek" — link ke daftar proyek.
- Dropdown `[⋯]`: Edit, Hapus. Tambah/Edit via modal.

---

## 13. Profil (`/profile`)

**Tujuan:** Pengguna mengelola data diri dan melihat ringkasan pribadi.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  PROFIL SAYA                                │ │
│             │  │  ┌──────────┐  Fauzan Rahman               │ │
│             │  │  │   🧑     │  fauzan@student.edu          │ │
│             │  │  │  [Ubah]  │  NIM: 12345678               │ │
│             │  │  └──────────┘  Semester 6 · Kelas A        │ │
│             │  │  [Edit Profil]  [Ganti Password]            │ │
│             │  └────────────────────────────────────────────┘ │
│             │                                                  │
│             │  ┌──────────────────┐ ┌──────────────────────┐  │
│             │  │  📊 STATISTIK    │ │  📋 PROYEK SAYA      │  │
│             │  │  3 Proyek Aktif  │ │  (list proyek user)  │  │
│             │  │  12 Tugas        │ │                      │  │
│             │  │  9 Selesai       │ └──────────────────────┘  │
│             │  │  75% Completion  │                            │
│             │  └──────────────────┘                            │
│             │                                                  │
│             │  ┌────────────────────────────────────────────┐ │
│             │  │  ✅ TUGAS SAYA (5 terbaru)                  │ │
│             │  └────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## 14. Laporan (`/reports`)

**Tujuan:** Ringkasan progres, statistik, dan rekap.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  📈 Laporan & Ringkasan                          │
│             │                                                  │
│             │  OVERVIEW (grid 2x2): Total Proyek, Total Tugas │
│             │  Tugas Selesai, Rata-rata Progress              │
│             │                                                  │
│             │  📊 PROGRESS PER PROYEK (progress bar list)     │
│             │                                                  │
│             │  👥 ANGGOTA PALING AKTIF (top 5)                │
│             │                                                  │
│             │  📚 REKAP PER MATA KULIAH & DOSEN (tabel)       │
└──────────────────────────────────────────────────────────────┘
```

---

## 15. Riwayat Aktivitas (`/activities`)

**Tujuan:** Log semua aktivitas di seluruh sistem.

### Struktur Layout:

```
┌──────────────────────────────────────────────────────────────┐
│  [Sidebar]  │  MAIN CONTENT                                   │
│             │                                                  │
│             │  🕐 Riwayat Aktivitas                            │
│             │                                                  │
│             │  [Filter: Semua ▾]  [Proyek: Semua ▾]          │
│             │                                                  │
│             │  TIMELINE (garis vertikal di kiri)               │
│             │                                                  │
│             │  ●  Hari ini — 15 Juni 2026                     │
│             │  ├── 14:30  Fauzan membuat tugas "Buat ERD"     │
│             │  ├── 13:15  Rina mengubah status "Wireframe"    │
│             │  ├── 10:00  Fauzan menambahkan Ani sebagai      │
│             │  │    anggota di proyek Basis Data              │
│             │  │                                              │
│             │  ●  Kemarin — 14 Juni 2026                      │
│             │  ├── 16:45  Budi mengubah deadline "Setup DB"   │
│             │  ├── 09:30  Proyek "Pemrograman Web Lanjut"     │
│             │  │    dibuat oleh Fauzan                        │
└──────────────────────────────────────────────────────────────┘
```

---

## 📱 Mobile Responsive

Untuk viewport < 768px:

1. **Sidebar hilang** → bottom navigation bar (fixed, height ~56px) dengan 5 ikon: Dashboard, Proyek, Tugas, Laporan, Profil.
2. **Grid 2-3 kolom** → jadi 1 kolom.
3. **Tabel** → jadi card list.
4. **Form** → full width, padding 16px.
5. **Stat row** → grid 2x2.
6. **Font heading** → dikurangi 2-4px.
7. **Tombol** → min 44px touch target.

---

> **Catatan:** Semua halaman menggunakan layout sidebar + main content yang sama. Sidebar hanya ditampilkan jika user sudah login. Landing page, Login, dan Register tidak menggunakan sidebar.
