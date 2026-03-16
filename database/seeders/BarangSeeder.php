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
        // Data barang lengkap dengan kode dan stok
        $barangs = [
            // MIE INSTAN
            ['kode' => 'BRG001', 'nama' => 'Indomie Goreng Original', 'harga' => 3500],
            ['kode' => 'BRG002', 'nama' => 'Indomie Mie Instan Original', 'harga' => 3200],
            ['kode' => 'BRG003', 'nama' => 'Indomie Kari Ayam', 'harga' => 3500],
            ['kode' => 'BRG004', 'nama' => 'Indomie Ayam Bawang', 'harga' => 3200],
            ['kode' => 'BRG005', 'nama' => 'Mie Sedap Goreng', 'harga' => 3500],
            ['kode' => 'BRG006', 'nama' => 'Mie Sedap Ayam', 'harga' => 3200],

            // MINUMAN
            ['kode' => 'BRG007', 'nama' => 'Aqua 600ml', 'harga' => 4000],
            ['kode' => 'BRG008', 'nama' => 'Aqua 1500ml', 'harga' => 6000],
            ['kode' => 'BRG009', 'nama' => 'Aqua Galon 19L', 'harga' => 20000],
            ['kode' => 'BRG010', 'nama' => 'Teh Botol Sosro 450ml', 'harga' => 5000],
            ['kode' => 'BRG011', 'nama' => 'Teh Botol Sosro 1L', 'harga' => 9000],
            ['kode' => 'BRG012', 'nama' => 'Pocari Sweat 500ml', 'harga' => 8000],
            ['kode' => 'BRG013', 'nama' => 'Pocari Sweat 1L', 'harga' => 15000],
            ['kode' => 'BRG014', 'nama' => 'Bear Brand 350ml', 'harga' => 6000],
            ['kode' => 'BRG015', 'nama' => 'Coca Cola 390ml', 'harga' => 7000],
            ['kode' => 'BRG016', 'nama' => 'Sprite 390ml', 'harga' => 7000],
            ['kode' => 'BRG017', 'nama' => 'Fanta 390ml', 'harga' => 7000],
            ['kode' => 'BRG018', 'nama' => 'Ultra Milk 250ml Coklat', 'harga' => 5000],
            ['kode' => 'BRG019', 'nama' => 'Ultra Milk 250ml Strawberry', 'harga' => 5000],
            ['kode' => 'BRG020', 'nama' => 'Ultra Milk 250ml Vanilla', 'harga' => 5000],
            ['kode' => 'BRG021', 'nama' => 'Good Day 250ml Original', 'harga' => 4000],
            ['kode' => 'BRG022', 'nama' => 'Good Day 250ml Cappuccino', 'harga' => 4000],
            ['kode' => 'BRG023', 'nama' => 'Kopi Kapal Api 10x23gr', 'harga' => 15000],
            ['kode' => 'BRG024', 'nama' => 'Torabika 10x25gr', 'harga' => 16000],
            ['kode' => 'BRG025', 'nama' => 'Tea Jus 350ml Apel', 'harga' => 4000],
            ['kode' => 'BRG026', 'nama' => 'Tea Jus 350ml Anggur', 'harga' => 4000],
            ['kode' => 'BRG027', 'nama' => 'Tea Jus 350ml Leci', 'harga' => 4000],
            ['kode' => 'BRG028', 'nama' => 'Fruit Tea 350ml Peach', 'harga' => 5000],
            ['kode' => 'BRG029', 'nama' => 'Fruit Tea 350ml Apple', 'harga' => 5000],
            ['kode' => 'BRG030', 'nama' => 'You C1000 100ml Orange', 'harga' => 6000],
            ['kode' => 'BRG031', 'nama' => 'Le Minerale 600ml', 'harga' => 4000],
            ['kode' => 'BRG032', 'nama' => 'Vit Air 600ml', 'harga' => 4000],
            ['kode' => 'BRG033', 'nama' => 'Club 1000ml', 'harga' => 5000],

            # SNACK
            ['kode' => 'BRG034', 'nama' => 'Chitato 68gr Original', 'harga' => 10000],
            ['kode' => 'BRG035', 'nama' => 'Chitato 68gr Barbecue', 'harga' => 10000],
            ['kode' => 'BRG036', 'nama' => 'Chitato 68gr Keju', 'harga' => 10000],
            ['kode' => 'BRG037', 'nama' => 'Lays 68gr Original', 'harga' => 10000],
            ['kode' => 'BRG038', 'nama' => 'Lays 68gr Barbecue', 'harga' => 10000],
            ['kode' => 'BRG039', 'nama' => 'Lays 68gr Sour Cream', 'harga' => 10000],
            ['kode' => 'BRG040', 'nama' => 'Qtela 75gr Original', 'harga' => 8000],
            ['kode' => 'BRG041', 'nama' => 'Qtela 75gr Balado', 'harga' => 8000],
            ['kode' => 'BRG042', 'nama' => 'Jetz 70gr Keju', 'harga' => 8000],
            ['kode' => 'BRG043', 'nama' => 'Jetz 70gr Coklat', 'harga' => 8000],
            ['kode' => 'BRG044', 'nama' => 'Zeah 70gr Ayam Bawang', 'harga' => 8000],
            ['kode' => 'BRG045', 'nama' => 'Malkist 130gr Coklat', 'harga' => 7000],
            ['kode' => 'BRG046', 'nama' => 'Malkist 130gr Keju', 'harga' => 7000],
            ['kode' => 'BRG047', 'nama' => 'Malkist Crackers 130gr', 'harga' => 7000],
            ['kode' => 'BRG048', 'nama' => 'Khong Guan Biskuit 140gr', 'harga' => 9000],
            ['kode' => 'BRG049', 'nama' => 'Oreo 133gr Original', 'harga' => 12000],
            ['kode' => 'BRG050', 'nama' => 'Better 70gr Coklat', 'harga' => 8000],
            ['kode' => 'BRG051', 'nama' => 'Roma Kelapa 160gr', 'harga' => 8000],
            ['kode' => 'BRG052', 'nama' => 'Nissin Wafer Coklat 150gr', 'harga' => 9000],
            ['kode' => 'BRG053', 'nama' => 'Tugu Gereja Wafer Vanilla 140gr', 'harga' => 8000],
            ['kode' => 'BRG054', 'nama' => 'Monde 145gr Butter', 'harga' => 9000],
            ['kode' => 'BRG055', 'nama' => 'Kalpa 150gr Coklat', 'harga' => 8000],
            ['kode' => 'BRG056', 'nama' => 'Kraft Cheese 170gr', 'harga' => 22000],
            ['kode' => 'BRG057', 'nama' => 'Choco Mania 100gr', 'harga' => 8000],
            ['kode' => 'BRG058', 'nama' => 'Shimmy 120gr Keju', 'harga' => 7000],
            ['kode' => 'BRG059', 'nama' => 'Taro 100gr Net', 'harga' => 8000],
            ['kode' => 'BRG060', 'nama' => 'Potabee 70gr Original', 'harga' => 7000],

            # MAKANAN RINGAN
            ['kode' => 'BRG061', 'nama' => 'Roti Tawar Sari Roti 380gr', 'harga' => 15000],
            ['kode' => 'BRG062', 'nama' => 'Roti Tawar Gardenia 380gr', 'harga' => 15000],
            ['kode' => 'BRG063', 'nama' => 'Roti Coklat Sari Roti', 'harga' => 6000],
            ['kode' => 'BRG064', 'nama' => 'Roti Sosis Sari Roti', 'harga' => 6000],
            ['kode' => 'BRG065', 'nama' => 'Roti Keju Sari Roti', 'harga' => 6000],
            ['kode' => 'BRG066', 'nama' => 'Bolu Marmer 250gr', 'harga' => 12000],
            ['kode' => 'BRG067', 'nama' => 'Bolu Coklat 250gr', 'harga' => 12000],
            ['kode' => 'BRG068', 'nama' => 'Roti Bakar Coklat Keju', 'harga' => 8000],
            ['kode' => 'BRG069', 'nama' => 'Donat Kentang Mini', 'harga' => 3000],
            ['kode' => 'BRG070', 'nama' => 'Donat Glaze Original', 'harga' => 4000],
            ['kode' => 'BRG071', 'nama' => 'Pie Buah Apel', 'harga' => 5000],
            ['kode' => 'BRG072', 'nama' => 'Pie Coklat', 'harga' => 5000],
            ['kode' => 'BRG073', 'nama' => 'Pudding Coklat 150gr', 'harga' => 8000],
            ['kode' => 'BRG074', 'nama' => 'Pudding Vanilla 150gr', 'harga' => 8000],

            # TOILETRIES
            ['kode' => 'BRG075', 'nama' => 'Pepsodent 190gr', 'harga' => 15000],
            ['kode' => 'BRG076', 'nama' => 'Close Up 160gr', 'harga' => 15000],
            ['kode' => 'BRG077', 'nama' => 'Sensodyne 100gr', 'harga' => 35000],
            ['kode' => 'BRG078', 'nama' => 'Colgate 180gr', 'harga' => 18000],
            ['kode' => 'BRG079', 'nama' => 'Sikat Gigi Pepsodent', 'harga' => 12000],
            ['kode' => 'BRG080', 'nama' => 'Sikat Gigi Colgate', 'harga' => 12000],
            ['kode' => 'BRG081', 'nama' => 'Lifebuoy Sabun 85gr', 'harga' => 5000],
            ['kode' => 'BRG082', 'nama' => 'Lux Sabun 85gr', 'harga' => 6000],
            ['kode' => 'BRG083', 'nama' => 'Dove Sabun 90gr', 'harga' => 10000],
            ['kode' => 'BRG084', 'nama' => 'Giv Sabun 80gr', 'harga' => 5000],
            ['kode' => 'BRG085', 'nama' => 'Nuvo Sabun 80gr', 'harga' => 5000],
            ['kode' => 'BRG086', 'nama' => 'Sunlight 800ml', 'harga' => 18000],
            ['kode' => 'BRG087', 'nama' => 'Molto 800ml', 'harga' => 18000],
            ['kode' => 'BRG088', 'nama' => 'Daia 800gr', 'harga' => 18000],
            ['kode' => 'BRG089', 'nama' => 'Rinso 800gr', 'harga' => 18000],
            ['kode' => 'BRG090', 'nama' => 'Attack 800gr', 'harga' => 18000],
            ['kode' => 'BRG091', 'nama' => 'Soful 800gr', 'harga' => 16000],
            ['kode' => 'BRG092', 'nama' => 'Downy 750ml', 'harga' => 20000],
            ['kode' => 'BRG093', 'nama' => 'Molto Softener 750ml', 'harga' => 18000],
            ['kode' => 'BRG094', 'nama' => 'Pantene 170ml', 'harga' => 25000],
            ['kode' => 'BRG095', 'nama' => 'Rejoice 170ml', 'harga' => 20000],
            ['kode' => 'BRG096', 'nama' => 'Sunsilk 170ml', 'harga' => 22000],
            ['kode' => 'BRG097', 'nama' => 'Clear 170ml', 'harga' => 22000],
            ['kode' => 'BRG098', 'nama' => 'Lifebuoy Sampo 170ml', 'harga' => 18000],
            ['kode' => 'BRG099', 'nama' => 'Vixal 320ml', 'harga' => 12000],
            ['kode' => 'BRG100', 'nama' => 'Wipol 750ml', 'harga' => 15000],

            # HOUSEHOLD
            ['kode' => 'BRG101', 'nama' => 'Tisu Wajah 250gr', 'harga' => 12000],
            ['kode' => 'BRG102', 'nama' => 'Tisu Toilet 250gr', 'harga' => 15000],
            ['kode' => 'BRG103', 'nama' => 'Tisu Dapur 200gr', 'harga' => 10000],
            ['kode' => 'BRG104', 'nama' => 'Popok Bayi M 10pcs', 'harga' => 35000],
            ['kode' => 'BRG105', 'nama' => 'Popok Bayi L 10pcs', 'harga' => 35000],
            ['kode' => 'BRG106', 'nama' => 'Popok Bayi XL 9pcs', 'harga' => 35000],
            ['kode' => 'BRG107', 'nama' => 'Pembalut Wanita 20pcs', 'harga' => 15000],
            ['kode' => 'BRG108', 'nama' => 'Pembalut Wanita Overnight 12pcs', 'harga' => 15000],
            ['kode' => 'BRG109', 'nama' => 'Kapas 150gr', 'harga' => 8000],
            ['kode' => 'BRG110', 'nama' => 'Alkohol 70% 500ml', 'harga' => 15000],
            ['kode' => 'BRG111', 'nama' => 'Betadine 30ml', 'harga' => 20000],
            ['kode' => 'BRG112', 'nama' => 'Handsanitizer 50ml', 'harga' => 10000],
            ['kode' => 'BRG113', 'nama' => 'Masker Medis 3ply Box', 'harga' => 25000],
            ['kode' => 'BRG114', 'nama' => 'Minyak Goreng 1L', 'harga' => 18000],
            ['kode' => 'BRG115', 'nama' => 'Minyak Goreng 2L', 'harga' => 35000],
            ['kode' => 'BRG116', 'nama' => 'Bimoli 1L', 'harga' => 18000],
            ['kode' => 'BRG117', 'nama' => 'Bimoli 2L', 'harga' => 35000],
            ['kode' => 'BRG118', 'nama' => 'Kecap Manis 500ml', 'harga' => 20000],
            ['kode' => 'BRG119', 'nama' => 'Saus Tomat 340gr', 'harga' => 15000],
            ['kode' => 'BRG120', 'nama' => 'Saus Sambal 340gr', 'harga' => 15000],
            ['kode' => 'BRG121', 'nama' => 'Tepung Terigu 1kg', 'harga' => 12000],
            ['kode' => 'BRG122', 'nama' => 'Tepung Beras 1kg', 'harga' => 15000],
            ['kode' => 'BRG123', 'nama' => 'Gula Pasir 1kg', 'harga' => 14000],
            ['kode' => 'BRG124', 'nama' => 'Gula Merah 250gr', 'harga' => 8000],
            ['kode' => 'BRG125', 'nama' => 'Garam Halus 500gr', 'harga' => 3000],
            ['kode' => 'BRG126', 'nama' => 'Penyedap Rasa 550gr', 'harga' => 10000],
            ['kode' => 'BRG127', 'nama' => 'Kecap Asin 600ml', 'harga' => 18000],
            ['kode' => 'BRG128', 'nama' => 'Kecap Ikan 600ml', 'harga' => 18000],
            ['kode' => 'BRG129', 'nama' => 'Teh Celup 25', 'harga' => 15000],
            ['kode' => 'BRG130', 'nama' => 'Kopi Hitam Tube 10', 'harga' => 10000],

            # STATIONERY
            ['kode' => 'BRG131', 'nama' => 'Pensil HB 2B', 'harga' => 5000],
            ['kode' => 'BRG132', 'nama' => 'Pulpen Standard Hitam', 'harga' => 3000],
            ['kode' => 'BRG133', 'nama' => 'Pulpen Standard Biru', 'harga' => 3000],
            ['kode' => 'BRG134', 'nama' => 'Pulpen Standard Merah', 'harga' => 3000],
            ['kode' => 'BRG135', 'nama' => 'Penggaris 30cm', 'harga' => 5000],
            ['kode' => 'BRG136', 'nama' => 'Penghapus', 'harga' => 3000],
            ['kode' => 'BRG137', 'nama' => 'Rautan', 'harga' => 3000],
            ['kode' => 'BRG138', 'nama' => 'Buku Tulis 70gr', 'harga' => 4000],
            ['kode' => 'BRG139', 'nama' => 'Buku Tulis 80gr', 'harga' => 5000],
            ['kode' => 'BRG140', 'nama' => 'Buku Catatan Sidu', 'harga' => 7000],
            ['kode' => 'BRG141', 'nama' => 'Klip HVS', 'harga' => 4000],
            ['kode' => 'BRG142', 'nama' => 'Map Plastik', 'harga' => 3000],
            ['kode' => 'BRG143', 'nama' => 'Lakban Bening', 'harga' => 8000],
            ['kode' => 'BRG144', 'nama' => 'Lakban Coklat', 'harga' => 8000],
            ['kode' => 'BRG145', 'nama' => 'Snip Glue', 'harga' => 5000],
            ['kode' => 'BRG146', 'nama' => 'Kertas HVS A4 70gr 1 Rim', 'harga' => 45000],
            ['kode' => 'BRG147', 'nama' => 'Kertas HVS F4 70gr 1 Rim', 'harga' => 45000],
            ['kode' => 'BRG148', 'nama' => 'Kertas Photo A4 50lbr', 'harga' => 35000],
            ['kode' => 'BRG149', 'nama' => 'Stabillo Boss', 'harga' => 5000],
            ['kode' => 'BRG150', 'nama' => 'Spidol Permanent', 'harga' => 8000],
        ];

        foreach ($barangs as $barang) {
            Barang::updateOrCreate(
                ['kode' => $barang['kode']],
                [
                    'nama' => $barang['nama'],
                    'harga' => $barang['harga']
                ]
            );
        }

        $this->command->info('Berhasil menambahkan ' . count($barangs) . ' data barang.');
    }
}
