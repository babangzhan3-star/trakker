<p style="font-size:13px;color:var(--slate-500);margin-bottom:16px;">
    Setelah akun dihapus, semua data akan hilang permanen. Pastikan kamu sudah mem-backup data yang diperlukan.
</p>

<button class="btn btn-outline-danger" style="border-radius:8px;font-size:13px;padding:8px 16px;"
        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
    <i class="bi-trash me-1"></i> Hapus Akun
</button>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:14px;">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf @method('delete')

                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold">Hapus Akun?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <p style="font-size:14px;color:var(--slate-500);">Semua data akan hilang permanen. Masukkan password untuk konfirmasi.</p>
                    <input type="password" name="password" class="form-control form-control-custom"
                           placeholder="Password" required>
                    @error('password', 'userDeletion')
                        <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger" style="border-radius:8px;font-size:13px;padding:8px 16px;">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
</div>
