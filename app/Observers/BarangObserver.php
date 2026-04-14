<?php

namespace App\Observers;

use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangObserver
{
    /**
     * Handle the Barang "creating" event.
     * Generate automatic kode barang dengan format: BRG-XXXXXX
     */
    public function creating(Barang $barang): void
    {
        if (empty($barang->kode)) {
            $barang->kode = $this->generateKodeBarang();
        }
    }

    /**
     * Generate kode barang unik dengan format BRG-XXXXXX
     * XXXXXX adalah nomor urut 6 digit
     */
    private function generateKodeBarang(): string
    {
        // Cari nomor urut terakhir
        $lastKode = Barang::orderBy('kode', 'desc')->value('kode');

        if ($lastKode) {
            // Extract nomor urut dari kode terakhir (contoh: BRG-000001 -> 000001)
            $lastNumber = (int) substr($lastKode, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format nomor urut dengan 6 digit (000001, 000002, dst)
        return 'BRG-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Handle the Barang "created" event.
     */
    public function created(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "deleted" event.
     */
    public function deleted(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "restored" event.
     */
    public function restored(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "force deleted" event.
     */
    public function forceDeleted(Barang $barang): void
    {
        //
    }
}
