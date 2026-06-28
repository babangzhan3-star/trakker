@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-heading mb-1">👥 Manajemen Pengguna</h1>
        <p class="page-subtitle">{{ $users->total() }} pengguna terdaftar</p>
    </div>
    <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalUser">
        <i class="bi-plus-lg me-1"></i> Tambah Pengguna
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
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, email, atau NIM..."
                       value="{{ request('search') }}" style="border-radius:0 8px 8px 0;border-color:#CBD5E1;padding:10px 14px;">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn-outline-custom w-100">
                <i class="bi-search me-1"></i> Cari
            </button>
        </div>
    </form>
</div>

{{-- Error --}}
@if(session('error'))
    <div class="alert alert-danger border-0" style="background:#FEE2E2;color:#991B1B;border-radius:10px;font-size:14px;">
        <i class="bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
    </div>
@endif

{{-- Tabel --}}
<div class="card-clean p-0 overflow-hidden">
    @if($users->count() > 0)
    <table class="table-modern mb-0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Semester</th>
                <th>Proyek</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @php $c = ['#EFF6FF','#F0FDF4','#FEF3C7','#FCE7F3','#EDE9FE']; $tc = ['#2563EB','#059669','#D97706','#DB2777','#7C3AED']; $i = $user->id % 5; @endphp
                        <div style="width:32px;height:32px;border-radius:50%;background:{{ $c[$i] }};color:{{ $tc[$i] }};
                                    display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;flex-shrink:0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <strong>{{ $user->name }}</strong>
                    </div>
                </td>
                <td style="font-size:13px;">{{ $user->email }}</td>
                <td><span class="badge-status badge-review">{{ $user->nim ?? '—' }}</span></td>
                <td>{{ $user->semester ?? '—' }}</td>
                <td>
                    @if($user->projects_count > 0)
                        <span class="badge-status badge-proses">{{ $user->projects_count }} proyek</span>
                    @else
                        <span class="text-muted">0</span>
                    @endif
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-custom btn-sm-custom me-1"
                            onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->nim }}', {{ $user->semester ?? 'null' }}, '{{ $user->kelas }}')">
                        <i class="bi-pencil"></i>
                    </button>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-custom btn-sm-custom text-danger"
                                {{ $user->id === Auth::id() ? 'disabled title=Tidak bisa hapus akun sendiri' : '' }}>
                            <i class="bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-5">
        <i class="bi-people" style="font-size:48px;color:var(--slate-300);"></i>
        <p class="mt-3 text-muted">Belum ada pengguna.</p>
    </div>
    @endif
</div>

@if($users->hasPages())
    <div class="mt-3">{{ $users->links() }}</div>
@endif

{{-- MODAL Tambah/Edit --}}
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;">
            <form id="formUser" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div id="methodFieldUser"></div>

                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold" id="modalUserTitle">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="inputName" class="form-control form-control-custom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="inputEmail" class="form-control form-control-custom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">NIM</label>
                        <input type="text" name="nim" id="inputNim" class="form-control form-control-custom">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:14px;">Semester</label>
                            <select name="semester" id="inputSemester" class="form-select form-control-custom">
                                <option value="">—</option>
                                @for($i = 1; $i <= 14; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium" style="font-size:14px;">Kelas</label>
                            <select name="kelas" id="inputKelas" class="form-select form-control-custom">
                                <option value="">—</option>
                                @foreach(['A','B','C','D','E'] as $k)
                                    <option value="{{ $k }}">{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Password <span class="text-danger" id="pwRequired">*</span></label>
                        <input type="password" name="password" id="inputPassword" class="form-control form-control-custom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" style="font-size:14px;">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="inputPasswordConf" class="form-control form-control-custom">
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
    const modalUserEl = document.getElementById('modalUser');
    const formUser = document.getElementById('formUser');
    const modalUserTitle = document.getElementById('modalUserTitle');
    const methodFieldUser = document.getElementById('methodFieldUser');
    const pwRequired = document.getElementById('pwRequired');
    const inputPassword = document.getElementById('inputPassword');
    const inputPasswordConf = document.getElementById('inputPasswordConf');

    modalUserEl.addEventListener('show.bs.modal', function(e) {
        if (e.relatedTarget && e.relatedTarget.getAttribute('data-bs-target')) {
            formUser.action = '{{ route('users.store') }}';
            methodFieldUser.innerHTML = '';
            modalUserTitle.textContent = 'Tambah Pengguna';
            pwRequired.style.display = '';
            inputPassword.required = true;
            inputPasswordConf.required = true;
            document.getElementById('inputName').value = '';
            document.getElementById('inputEmail').value = '';
            document.getElementById('inputNim').value = '';
            document.getElementById('inputSemester').value = '';
            document.getElementById('inputKelas').value = '';
            inputPassword.value = '';
            inputPasswordConf.value = '';
        }
    });

    function editUser(id, name, email, nim, semester, kelas) {
        formUser.action = '/users/' + id;
        methodFieldUser.innerHTML = '@method("PUT")';
        modalUserTitle.textContent = 'Edit Pengguna';
        pwRequired.style.display = 'none';
        inputPassword.required = false;
        inputPasswordConf.required = false;

        document.getElementById('inputName').value = name;
        document.getElementById('inputEmail').value = email;
        document.getElementById('inputNim').value = nim || '';
        document.getElementById('inputSemester').value = semester || '';
        document.getElementById('inputKelas').value = kelas || '';
        inputPassword.value = '';
        inputPasswordConf.value = '';

        const modal = new bootstrap.Modal(modalUserEl);
        modal.show();
    }
</script>
@endpush
