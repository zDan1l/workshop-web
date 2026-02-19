<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom email jika belum ada
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique();
            }

            // Tambah kolom otp varchar(6), nullable karena tidak selalu terisi
            $table->string('otp', 6)->nullable()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('otp');
        });
    }
};
