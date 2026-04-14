<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;

class BarangResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua data barang yang ada
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE'); // Untuk PostgreSQL
        Barang::truncate();
        DB::statement('SET CONSTRAINTS ALL DEFERRED'); // Untuk PostgreSQL

        // Reset sequence
        DB::statement("ALTER SEQUENCE barangs_id_barang_seq RESTART WITH 1");

        $barangs = [
            ['nama' => 'Minyak Goreng 2L', 'harga' => 35000],
            ['nama' => 'Gula Pasir 1kg', 'harga' => 15000],
            ['nama' => 'Tepung Terigu 1kg', 'harga' => 12000],
            ['nama' => 'Kecap Manis 500ml', 'harga' => 22000],
            ['nama' => 'Garam Halus 250gr', 'harga' => 5000],
            ['nama' => 'Beras Premium 5kg', 'harga' => 75000],
            ['nama' => 'Telur Ayam 1kg', 'harga' => 28000],
            ['nama' => 'Susu UHT 1L', 'harga' => 18000],
            ['nama' => 'Mie Instan Goreng', 'harga' => 3500],
            ['nama' => 'Kopi Sachet', 'harga' => 2000],
            ['nama' => 'Teh Botol 450ml', 'harga' => 4000],
            ['nama' => 'Air Mineral 600ml', 'harga' => 3500],
            ['nama' => 'Roti Tawar', 'harga' => 15000],
            ['nama' => 'Mentega 200gr', 'harga' => 18000],
            ['nama' => 'Madu Murni 250ml', 'harga' => 85000],
            ['nama' => 'Sambal Botol', 'harga' => 12000],
            ['nama' => 'Bawang Merah 250gr', 'harga' => 25000],
            ['nama' => 'Bawang Putih 250gr', 'harga' => 32000],
            ['nama' => 'Cabe Rawit 100gr', 'harga' => 15000],
            ['nama' => 'Tomat 500gr', 'harga' => 8000],
            ['nama' => 'Kentang 1kg', 'harga' => 15000],
            ['nama' => 'Wortel 500gr', 'harga' => 10000],
            ['nama' => 'Brokoli 250gr', 'harga' => 12000],
            ['nama' => 'Ayam Potong 1kg', 'harga' => 45000],
            ['nama' => 'Daging Sapi 500gr', 'harga' => 120000],
            ['nama' => 'Ikan Tongkol 500gr', 'harga' => 35000],
        ];

        // Insert data barang - kode akan digenerate otomatis oleh observer
        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        $this->command->info('Berhasil reset dan menambahkan ' . count($barangs) . ' data barang.');
        $this->command->info('Format kode baru: BRG-XXXXXX (6 digit)');
    }
}
