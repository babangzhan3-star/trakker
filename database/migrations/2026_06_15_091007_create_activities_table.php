<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('tipe', [
                'proyek_dibuat', 'proyek_diedit',
                'tugas_dibuat', 'tugas_diedit',
                'status_diubah', 'deadline_diubah',
                'anggota_ditambahkan', 'anggota_dihapus'
            ]);
            $table->string('deskripsi');
            $table->timestamp('created_at')->nullable();

            $table->index('user_id');
            $table->index('project_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
