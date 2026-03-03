<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            ['nama' => 'Mie Instan Original', 'harga' => 3500],
            ['nama' => 'Mie Instan Goreng', 'harga' => 3500],
            ['nama' => 'Susu UHT Coklat 200ml', 'harga' => 5000],
            ['nama' => 'Susu UHT Strawberry 200ml', 'harga' => 5000],
            ['nama' => 'Teh Botol Sosro 500ml', 'harga' => 4500],
            ['nama' => 'Air Mineral 600ml', 'harga' => 3000],
            ['nama' => 'Kopi Sachet 3 in 1', 'harga' => 2000],
            ['nama' => 'Biskuit Marie 300gr', 'harga' => 8000],
            ['nama' => 'Wafer Coklat 200gr', 'harga' => 6500],
            ['nama' => 'Keripik Kentang Original 75gr', 'harga' => 10000],
            ['nama' => 'Sabun Mandi Cair 250ml', 'harga' => 15000],
            ['nama' => 'Sampo Anti Ketombe 170ml', 'harga' => 12000],
            ['nama' => 'Pasta Gigi Herbal 190gr', 'harga' => 8500],
            ['nama' => 'Sikat Gigi Lembut', 'harga' => 5500],
            ['nama' => 'Deterjen Bubuk 800gr', 'harga' => 18000],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
