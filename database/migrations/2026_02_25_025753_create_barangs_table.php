<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama');
            $table->integer('harga');
            $table->timestamps();
        });

        // Drop the old function if it exists
        DB::unprepared('DROP FUNCTION IF EXISTS fn_generate_id_barang() CASCADE');

        // Create or replace the function with correct table name
        DB::unprepared('
            CREATE OR REPLACE FUNCTION fn_generate_id_barang()
            RETURNS TRIGGER AS $$
            DECLARE
                count_today INTEGER;
                new_number INTEGER;
            BEGIN
                -- Count records created today
                SELECT count(*) INTO count_today
                FROM barangs
                WHERE "created_at"::date = CURRENT_DATE;
                
                -- Calculate new number
                new_number := count_today + 1;
                
                -- Set the id_barang with format YYYYMMDD-XXX
                -- NEW.id_barang := TO_CHAR(CURRENT_DATE, \'YYYYMMDD\') || \'-\' || LPAD(new_number::TEXT, 3, \'0\');
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Create trigger
        DB::unprepared('
            DROP TRIGGER IF EXISTS trg_generate_id_barang ON barangs;
            CREATE TRIGGER trg_generate_id_barang
            BEFORE INSERT ON barangs
            FOR EACH ROW
            EXECUTE FUNCTION fn_generate_id_barang();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_generate_id_barang ON barangs');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_generate_id_barang() CASCADE');
        Schema::dropIfExists('barangs');
    }
};
