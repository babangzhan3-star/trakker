@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
<h1 class="page-heading mb-1">⚙️ Pengaturan</h1>
<p class="page-subtitle mb-4">Kelola profil dan keamanan akun</p>

<div class="row g-4">
    {{-- Profil --}}
    <div class="col-lg-8">
        <div class="card-clean">
            <h6 class="fw-bold mb-3" style="font-size:16px;">Informasi Profil</h6>
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card-clean mt-4">
            <h6 class="fw-bold mb-3" style="font-size:16px;">Ubah Password</h6>
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="col-lg-4">
        <div class="card-clean text-center">
            @php $c = ['#EFF6FF','#F0FDF4','#FEF3C7']; $tc = ['#2563EB','#059669','#D97706']; @endphp
            <div style="width:80px;height:80px;border-radius:50%;background:{{ $c[0] }};color:{{ $tc[0] }};
                        display:inline-flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;margin-bottom:16px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
            <p class="text-muted mb-1" style="font-size:13px;">{{ Auth::user()->email }}</p>
            @if(Auth::user()->nim)
                <p class="text-muted mb-1" style="font-size:13px;">NIM: {{ Auth::user()->nim }}</p>
            @endif
            @if(Auth::user()->semester)
                <p class="text-muted mb-0" style="font-size:13px;">Semester {{ Auth::user()->semester }} · Kelas {{ Auth::user()->kelas ?? '—' }}</p>
            @endif
        </div>

        <div class="card-clean mt-3">
            <h6 class="fw-bold mb-3" style="font-size:16px;">Statistik</h6>
            {{-- MOCK API: stats passed from controller --}}
            <div class="d-flex justify-content-around text-center">
                <div>
                    <div style="font-size:20px;font-weight:700;">{{ $myProjects }}</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);">Proyek</small>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;">{{ $myTasks }}</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);">Tugas</small>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#059669;">{{ $myDone }}</div>
                    <small style="font-size:11px;text-transform:uppercase;color:var(--slate-500);">Selesai</small>
                </div>
            </div>
        </div>

        <div class="card-clean mt-3">
            <h6 class="fw-bold mb-3 text-danger" style="font-size:16px;">Zona Berbahaya</h6>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
