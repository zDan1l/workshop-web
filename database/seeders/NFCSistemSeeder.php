<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\SesiKuliah;
use Illuminate\Database\Seeder;

class NFCSistemSeeder extends Seeder
{
    public function run(): void
    {
        // Create Dosen
        $dosens = [
            ['nip' => '198001012005011001', 'nama' => 'Dr. Ahmad Susanto, M.Kom', 'email' => 'ahmad@univ.ac.id'],
            ['nip' => '198505202010121002', 'nama' => 'Prof. Budi Santoso, Ph.D', 'email' => 'budi@univ.ac.id'],
            ['nip' => '199002012019031003', 'nama' => 'Dr. Citra Dewi, M.T', 'email' => 'citra@univ.ac.id'],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }

        // Create Kelas
        $kelas1 = Kelas::create([
            'nama_kelas' => 'Pemrograman Web Lanjut',
            'kode_kelas' => 'TI-301',
            'dosen_id' => 1,
        ]);

        $kelas2 = Kelas::create([
            'nama_kelas' => 'Basis Data Terdistribusi',
            'kode_kelas' => 'TI-302',
            'dosen_id' => 2,
        ]);

        $kelas3 = Kelas::create([
            'nama_kelas' => 'Kecerdasan Buatan',
            'kode_kelas' => 'TI-401',
            'dosen_id' => 3,
        ]);

        // Create Mahasiswa
        $mahasiswas = [
            ['nim' => '20210001', 'nama' => 'Andi Pratama', 'email' => 'andi@student.univ.ac.id', 'nfc_serial' => '04:A3:55:B2:C1'],
            ['nim' => '20210002', 'nama' => 'Budi Hartono', 'email' => 'budi.h@student.univ.ac.id', 'nfc_serial' => '04:B2:44:C1:D2'],
            ['nim' => '20210003', 'nama' => 'Citra Lestari', 'email' => 'citra@student.univ.ac.id', 'nfc_serial' => '04:C1:33:D2:E3'],
            ['nim' => '20210004', 'nama' => 'Dewi Anggraini', 'email' => 'dewi@student.univ.ac.id', 'nfc_serial' => '04:D0:22:E3:F4'],
            ['nim' => '20210005', 'nama' => 'Eko Prasetyo', 'email' => 'eko@student.univ.ac.id', 'nfc_serial' => '04:E1:11:F4:A5'],
            ['nim' => '20210006', 'nama' => 'Fitri Handayani', 'email' => 'fitri@student.univ.ac.id', 'nfc_serial' => null],
            ['nim' => '20210007', 'nama' => 'Gunawan Wijaya', 'email' => 'gunawan@student.univ.ac.id', 'nfc_serial' => null],
            ['nim' => '20210008', 'nama' => 'Hesti Purnamasari', 'email' => 'hesti@student.univ.ac.id', 'nfc_serial' => null],
        ];

        foreach ($mahasiswas as $mhs) {
            Mahasiswa::create($mhs);
        }

        // Assign mahasiswa to kelases
        $kelas1->mahasiswas()->attach([1, 2, 3, 4, 5]);
        $kelas2->mahasiswas()->attach([2, 3, 4, 6, 7]);
        $kelas3->mahasiswas()->attach([1, 5, 6, 7, 8]);

        // Create Sesi Kuliah for today
        $sesi1 = SesiKuliah::create([
            'kelas_id' => $kelas1->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->setHour(8)->setMinute(0)->setSecond(0),
            'jam_selesai' => now()->setHour(10)->setMinute(30)->setSecond(0),
            'is_aktif' => true,
        ]);

        $sesi2 = SesiKuliah::create([
            'kelas_id' => $kelas2->id,
            'tanggal' => now()->addDay()->toDateString(),
            'jam_mulai' => now()->setHour(13)->setMinute(0)->setSecond(0),
            'jam_selesai' => now()->setHour(15)->setMinute(30)->setSecond(0),
            'is_aktif' => false,
        ]);

        // Create sample absensi records
        Absensi::create([
            'mahasiswa_id' => 1,
            'sesi_id' => $sesi1->id,
            'waktu_scan' => now()->setHour(7)->setMinute(55)->setSecond(0),
            'status' => 'hadir',
            'nfc_serial_scanned' => '04:A3:55:B2:C1',
        ]);

        Absensi::create([
            'mahasiswa_id' => 2,
            'sesi_id' => $sesi1->id,
            'waktu_scan' => now()->setHour(8)->setMinute(20)->setSecond(0),
            'status' => 'terlambat',
            'nfc_serial_scanned' => '04:B2:44:C1:D2',
        ]);

        Absensi::create([
            'mahasiswa_id' => 3,
            'sesi_id' => $sesi1->id,
            'waktu_scan' => now()->setHour(7)->setMinute(58)->setSecond(0),
            'status' => 'hadir',
            'nfc_serial_scanned' => '04:C1:33:D2:E3',
        ]);

        $this->command->info('✅ NFC System seeded successfully!');
        $this->command->info('   - 3 Dosen');
        $this->command->info('   - 3 Kelas');
        $this->command->info('   - 8 Mahasiswa');
        $this->command->info('   - 2 Sesi Kuliah');
        $this->command->info('   - 3 Absensi records');
        $this->command->newLine();
        $this->command->info('📝 NFC Serial Numbers for testing:');
        $this->command->info('   - Andi: 04:A3:55:B2:C1');
        $this->command->info('   - Budi: 04:B2:44:C1:D2');
        $this->command->info('   - Citra: 04:C1:33:D2:E3');
        $this->command->info('   - Dewi: 04:D0:22:E3:F4');
        $this->command->info('   - Eko: 04:E1:11:F4:A5');
    }
}
