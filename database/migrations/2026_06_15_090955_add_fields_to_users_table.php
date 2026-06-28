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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim', 20)->nullable()->unique()->after('email');
            $table->unsignedTinyInteger('semester')->nullable()->after('nim');
            $table->string('kelas', 5)->nullable()->after('semester');
            $table->string('avatar')->nullable()->after('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'semester', 'kelas', 'avatar']);
        });
    }
};
