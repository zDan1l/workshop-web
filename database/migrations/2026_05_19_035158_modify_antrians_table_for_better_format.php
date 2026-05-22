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
        Schema::table('antrians', function (Blueprint $table) {
            // Add new columns for better queue management
            $table->integer('nomor_urut')->after('id')->default(1); // Daily queue number
            $table->date('tanggal')->after('nomor_urut')->default(DB::raw('CURRENT_DATE')); // Queue date

            // Add indexes for better performance
            $table->index(['tanggal', 'nomor_urut'], 'idx_daily_queue');
            $table->index('nomor_urut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropIndex('idx_daily_queue');
            $table->dropIndex(['nomor_urut']);
            $table->dropColumn(['nomor_urut', 'tanggal']);
        });
    }
};