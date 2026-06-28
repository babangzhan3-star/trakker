<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskFlow — Kelola Tugas Kelompok Tanpa Ribet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        :root { --primary: #2563EB; --slate-900: #0F172A; --slate-500: #64748B; --slate-50: #F8FAFC; }
        body { background: #fff; color: var(--slate-900); }
        .navbar { background: #fff; border-bottom: 1px solid #E2E8F0; height: 64px; }
        .navbar-brand { font-size: 20px; font-weight: 700; color: var(--primary) !important; }
        .btn-primary-custom { background: var(--primary); border: none; color: #fff; padding: 10px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: all 0.15s; }
        .btn-primary-custom:hover { background: #1D4ED8; color: #fff; transform: translateY(-1px); }
        .btn-outline-custom { background: #fff; border: 1px solid #E2E8F0; color: var(--slate-900); padding: 10px 24px; border-radius: 8px; font-weight: 500; font-size: 14px; }
        .btn-outline-custom:hover { background: var(--slate-50); }
        .hero { padding: 100px 0 80px; }
        .hero h1 { font-size: 48px; font-weight: 800; line-height: 1.2; margin-bottom: 20px; }
        .hero p { font-size: 18px; color: var(--slate-500); max-width: 520px; margin-bottom: 32px; line-height: 1.6; }
        .hero-illustration { width: 100%; max-width: 500px; height: 350px; background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 120px; }
        .section-title { font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 12px; }
        .section-sub { font-size: 16px; color: var(--slate-500); text-align: center; margin-bottom: 48px; }
        .feature-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; padding: 32px; transition: all 0.2s; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .feature-card .icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
        .feature-card h5 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .feature-card p { font-size: 14px; color: var(--slate-500); line-height: 1.6; margin-bottom: 0; }
        .cta { background: linear-gradient(135deg, #1D4ED8, #2563EB, #3B82F6); padding: 80px 0; text-align: center; color: #fff; margin-top: 80px; }
        .cta h2 { font-size: 32px; font-weight: 700; margin-bottom: 12px; }
        .cta p { font-size: 16px; opacity: 0.9; margin-bottom: 32px; }
        .footer { background: #1E293B; color: #94A3B8; text-align: center; padding: 20px 0; font-size: 13px; }
        @media (max-width: 768px) { .hero h1 { font-size: 32px; } .hero { padding: 60px 0 40px; } .hero-illustration { height: 200px; font-size: 80px; margin-top: 30px; } }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/"><i class="bi-kanban me-2"></i>TaskFlow</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="#fitur" style="font-size:14px;font-weight:500;">Fitur</a></li>
                @auth
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="btn-primary-custom text-decoration-none">Dashboard</a></li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn-primary-custom text-decoration-none">Masuk</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Kelola Tugas Kelompok Tanpa Ribet</h1>
                <p>TaskFlow bantu kamu dan tim menyelesaikan tugas kuliah lebih terorganisir. Pantau progres, bagi pekerjaan, dan jangan sampai ketinggalan deadline lagi.</p>
                <div class="d-flex gap-3 flex-wrap">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary-custom text-decoration-none" style="padding:14px 32px;font-size:16px;">Buka Dashboard <i class="bi-arrow-right ms-1"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-custom text-decoration-none" style="padding:14px 32px;font-size:16px;">Masuk Sekarang <i class="bi-arrow-right ms-1"></i></a>
                        <a href="#fitur" class="btn-outline-custom text-decoration-none" style="padding:14px 32px;font-size:16px;">Pelajari Lebih Lanjut</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <div class="hero-illustration mx-auto">📋</div>
            </div>
        </div>
    </div>
</section>

<section id="fitur" style="padding:80px 0;background:var(--slate-50);">
    <div class="container">
        <h2 class="section-title">Kenapa TaskFlow?</h2>
        <p class="section-sub">Dibuat khusus untuk mahasiswa yang sering kerja kelompok</p>
        <div class="row g-4">
            <div class="col-lg-4"><div class="feature-card h-100"><div class="icon" style="background:#EFF6FF;color:#2563EB;"><i class="bi-folder2"></i></div><h5>Manajemen Proyek</h5><p>Buat proyek untuk setiap tugas kelompok. Tentukan mata kuliah, dosen, dan anggota dengan mudah.</p></div></div>
            <div class="col-lg-4"><div class="feature-card h-100"><div class="icon" style="background:#F0FDF4;color:#059669;"><i class="bi-check2-square"></i></div><h5>Tracking Tugas</h5><p>Bagi-bagi tugas ke anggota, pantau status pengerjaan, dan lihat siapa yang belum menyelesaikan bagiannya.</p></div></div>
            <div class="col-lg-4"><div class="feature-card h-100"><div class="icon" style="background:#FEF3C7;color:#D97706;"><i class="bi-graph-up"></i></div><h5>Laporan Progres</h5><p>Lihat progress setiap proyek dalam satu dashboard. Ketahui anggota paling aktif dan tugas yang hampir deadline.</p></div></div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Siap atur tugas kelompokmu?</h2>
        <p>Daftar gratis dan mulai kelola proyek kuliah dengan lebih rapi.</p>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-light" style="padding:14px 40px;border-radius:8px;font-weight:600;font-size:16px;color:var(--primary);">Buka Dashboard <i class="bi-arrow-right ms-1"></i></a>
        @else
            <a href="{{ route('login') }}" class="btn btn-light" style="padding:14px 40px;border-radius:8px;font-weight:600;font-size:16px;color:var(--primary);">Masuk Sekarang <i class="bi-arrow-right ms-1"></i></a>
        @endauth
    </div>
</section>

<footer class="footer">
    <div class="container">&copy; {{ date('Y') }} TaskFlow &middot; Dibuat untuk tugas kuliah Pemrograman Web Lanjut</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
