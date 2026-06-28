@extends('layouts.app')
@section('title', 'Riwayat Aktivitas')

@section('content')
<h1 class="page-heading mb-1">🕐 Riwayat Aktivitas</h1>
<p class="page-subtitle mb-4">Semua aktivitas di proyek yang kamu ikuti</p>

{{-- Filter --}}
<div class="card-clean p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="tipe" class="form-select" style="border-radius:8px;border-color:#CBD5E1;font-size:14px;padding:10px 14px;">
                <option value="">Semua Tipe</option>
                <option value="proyek_dibuat" {{ request('tipe') == 'proyek_dibuat' ? 'selected' : '' }}>Proyek Dibuat</option>
                <option value="tugas_dibuat" {{ request('tipe') == 'tugas_dibuat' ? 'selected' : '' }}>Tugas Dibuat</option>
                <option value="status_diubah" {{ request('tipe') == 'status_diubah' ? 'selected' : '' }}>Status Diubah</option>
                <option value="anggota_ditambahkan" {{ request('tipe') == 'anggota_ditambahkan' ? 'selected' : '' }}>Anggota Ditambah</option>
                <option value="anggota_dihapus" {{ request('tipe') == 'anggota_dihapus' ? 'selected' : '' }}>Anggota Dihapus</option>
                <option value="deadline_diubah" {{ request('tipe') == 'deadline_diubah' ? 'selected' : '' }}>Deadline Diubah</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="project_id" class="form-select" style="border-radius:8px;border-color:#CBD5E1;font-size:14px;padding:10px 14px;">
                <option value="">Semua Proyek</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->nama_proyek }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-outline-custom w-100">
                <i class="bi-funnel me-1"></i> Filter
            </button>
        </div>
        @if(request()->anyFilled(['tipe', 'project_id']))
        <div class="col-md-2">
            <a href="{{ route('activities.index') }}" class="btn btn-sm text-muted">
                <i class="bi-x-lg me-1"></i> Reset
            </a>
        </div>
        @endif
    </form>
</div>

{{-- Timeline --}}
@if($activities->count() > 0)
    @php $currentDate = ''; @endphp
    <div class="card-clean" style="padding-left:0;">
        @foreach($activities as $act)
            @php
                $actDate = $act->created_at->isToday() ? 'Hari ini' :
                    ($act->created_at->isYesterday() ? 'Kemarin' : $act->created_at->translatedFormat('d F Y'));
            @endphp

            @if($currentDate !== $actDate)
                @php $currentDate = $actDate; @endphp
                <div class="px-4 py-2" style="font-size:14px;font-weight:600;color:var(--slate-700);margin-top:{{ $loop->first ? '0' : '8px' }};">
                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--primary);margin-right:8px;"></span>
                    {{ $actDate }}
                    @if($actDate !== 'Hari ini' && $actDate !== 'Kemarin')
                        — {{ $act->created_at->translatedFormat('Y') }}
                    @endif
                </div>
            @endif

            <div class="d-flex px-4 py-2" style="border-left:2px solid var(--slate-200);margin-left:18px;padding-left:20px!important;">
                <div style="flex:1;font-size:13px;">
                    <strong>{{ $act->user->name }}</strong>
                    <span class="text-muted">{{ $act->deskripsi }}</span>
                    @if($act->project)
                        <br><small style="color:var(--slate-400);">di <strong>{{ $act->project->nama_proyek }}</strong></small>
                    @endif
                </div>
                <small style="color:var(--slate-400);white-space:nowrap;font-size:12px;">
                    {{ $act->created_at->format('H:i') }}
                </small>
            </div>
        @endforeach
    </div>
@else
    <div class="card-clean text-center py-5">
        <i class="bi-clock-history" style="font-size:48px;color:var(--slate-300);"></i>
        <p class="mt-3 text-muted">Belum ada aktivitas.</p>
    </div>
@endif

@if($activities->hasPages())
    <div class="mt-3">
        {{ $activities->links() }}
    </div>
@endif
@endsection
