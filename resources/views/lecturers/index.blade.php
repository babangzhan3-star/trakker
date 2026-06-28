@extends('layouts.app')
@section('title', 'Dosen')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">👨‍🏫 Dosen</h1>
        <p class="page-subtitle">Kelola data dosen pengampu mata kuliah</p>
    </div>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalLecturer">
        <i class="bi-plus-lg me-1"></i> Tambah Dosen
    </button>
</div>

{{-- Search --}}
<div class="card-clean p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;">
                    <i class="bi-search" style="color:var(--slate-400);"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari dosen..."
                       value="{{ request('search') }}" style="border-radius:0 8px 8px 0;border-color:#CBD5E1;padding:10px 14px;">
            </div>
        </div>
    </form>
</div>

{{-- Card Grid --}}
@if($lecturers->count() > 0)
    <div class="row g-3">
        @foreach($lecturers as $lecturer)
        <div class="col-lg-4 col-md-6">
            <div class="card-clean text-center position-relative">
                {{-- Dropdown --}}
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="dropdown">
                        <button class="btn btn-sm text-muted" data-bs-toggle="dropdown" style="padding:2px 8px;">
                            <i class="bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius:10px;font-size:13px;">
                            <li><a class="dropdown-item py-2" href="#"
                                   onclick="editLecturer({{ $lecturer->id }}, '{{ $lecturer->nama }}', '{{ $lecturer->gelar }}', '{{ $lecturer->nidn }}', '{{ $lecturer->email }}', '{{ $lecturer->no_telp }}')">
                                <i class="bi-pencil me-2"></i> Edit
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST"
                                      onsubmit="return confirm('Hapus dosen {{ $lecturer->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item py-2 text-danger">
                                        <i class="bi-trash me-2"></i> Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Avatar --}}
                @php
                    $colors = ['#EFF6FF', '#F0FDF4', '#FEF3C7', '#FCE7F3', '#EDE9FE'];
                    $textColors = ['#2563EB', '#059669', '#D97706', '#DB2777', '#7C3AED'];
                    $idx = $lecturer->id % count($colors);
                @endphp
                <div style="width:72px;height:72px;border-radius:50%;background:{{ $colors[$idx] }};color:{{ $textColors[$idx] }};
                            display:inline-flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;margin-bottom:12px;">
                    {{ strtoupper(substr($lecturer->nama, 0, 1)) }}
                </div>

                <h5 class="fw-bold mb-0" style="font-size:16px;">{{ $lecturer->nama }}</h5>
                @if($lecturer->gelar)
                    <p class="text-muted mb-2" style="font-size:13px;">{{ $lecturer->gelar }}</p>
                @endif

                <div class="d-flex flex-column gap-1 mb-3" style="font-size:13px;">
                    @if($lecturer->nidn)
                        <span class="text-muted"><i class="bi-person-badge me-1"></i> NIDN: {{ $lecturer->nidn }}</span>
                    @endif
                    @if($lecturer->email)
                        <span class="text-muted"><i class="bi-envelope me-1"></i> {{ $lecturer->email }}</span>
                    @endif
                </div>

                <a href="{{ route('projects.index', ['lecturer' => $lecturer->id]) }}" class="text-decoration-none">
                    <span class="badge-status {{ $lecturer->projects_count > 0 ? 'badge-review' : 'badge-belum' }}">
                        <i class="bi-folder2 me-1"></i> {{ $lecturer->projects_count }} Proyek
                    </span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="card-clean text-center py-5">
        <i class="bi-person-workspace" style="font-size:48px;color:var(--slate-300);"></i>
        <p class="mt-3 text-muted">Belum ada data dosen. Tambahkan dosen pertama!</p>
    </div>
@endif

{{-- Pagination --}}
@if($lecturers->hasPages())
    <div class="mt-3">
        {{ $lecturers->links() }}
    </div>
@endif

{{-- MODAL: Tambah / Edit --}}
<div class="modal fade" id="modalLecturer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;">
            <form id="formLecturer" method="POST" action="{{ route('lecturers.store') }}">
                @csrf
                <div id="methodFieldLecturer"></div>

                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="modalLecturerTitle">Tambah Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="inputNama" class="form-control form-control-custom"
                               placeholder="e.g. Rina Anggraini" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:14px;">Gelar</label>
                            <input type="text" name="gelar" id="inputGelar" class="form-control form-control-custom"
                                   placeholder="e.g. M.Kom">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:14px;">NIDN</label>
                            <input type="text" name="nidn" id="inputNidn" class="form-control form-control-custom"
                                   placeholder="e.g. 0012088401">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-medium" style="font-size:14px;">Email</label>
                            <input type="email" name="email" id="inputEmail" class="form-control form-control-custom"
                                   placeholder="e.g. rina@kampus.ac.id">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-medium" style="font-size:14px;">No. Telp</label>
                            <input type="text" name="no_telp" id="inputNoTelp" class="form-control form-control-custom"
                                   placeholder="e.g. 08123456789">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalLecturerEl = document.getElementById('modalLecturer');
    const formLecturer = document.getElementById('formLecturer');
    const modalLecturerTitle = document.getElementById('modalLecturerTitle');
    const methodFieldLecturer = document.getElementById('methodFieldLecturer');
    const inputNama = document.getElementById('inputNama');
    const inputGelar = document.getElementById('inputGelar');
    const inputNidn = document.getElementById('inputNidn');
    const inputEmail = document.getElementById('inputEmail');
    const inputNoTelp = document.getElementById('inputNoTelp');

    modalLecturerEl.addEventListener('show.bs.modal', function(e) {
        if (e.relatedTarget && e.relatedTarget.getAttribute('data-bs-target')) {
            formLecturer.action = '{{ route('lecturers.store') }}';
            methodFieldLecturer.innerHTML = '';
            modalLecturerTitle.textContent = 'Tambah Dosen';
            inputNama.value = '';
            inputGelar.value = '';
            inputNidn.value = '';
            inputEmail.value = '';
            inputNoTelp.value = '';
        }
    });

    function editLecturer(id, nama, gelar, nidn, email, noTelp) {
        formLecturer.action = '/lecturers/' + id;
        methodFieldLecturer.innerHTML = '@method("PUT")';
        modalLecturerTitle.textContent = 'Edit Dosen';
        inputNama.value = nama;
        inputGelar.value = gelar;
        inputNidn.value = nidn;
        inputEmail.value = email;
        inputNoTelp.value = noTelp;

        const modal = new bootstrap.Modal(modalLecturerEl);
        modal.show();
    }
</script>
@endpush
