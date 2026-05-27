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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('sesi_id')->constrained('sesi_kuliahs')->onDelete('cascade');
            $table->timestamp('waktu_scan');
            $table->enum('status', ['hadir', 'terlambat', 'izin'])->default('hadir');
            $table->string('nfc_serial_scanned', 50);
            $table->timestamps();

            // Satu mahasiswa hanya bisa absen sekali per sesi
            $table->unique(['mahasiswa_id', 'sesi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
