<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Antrian extends Model
{
    protected $fillable = [
        'nomor_urut',      // Daily queue number (integer)
        'nomor_antrian',   // Formatted display (A0001, B0001, etc.)
        'tanggal',         // Queue date
        'nama',
        'status',
        'waktu_dipanggil'
    ];

    protected $casts = [
        'waktu_dipanggil' => 'datetime',
        'tanggal' => 'date',
    ];

    /**
     * Get formatted queue number for display
     * Example: A0001, B0001, C0001 (letter changes based on daily sequence)
     */
    public function getNomorFormattedAttribute()
    {
        $date = $this->tanggal ?? now()->toDateString();

        // Parse the date and get day of week (0 = Sunday, 1 = Monday, etc.)
        $carbonDate = \Carbon\Carbon::parse($date);
        $dayOfWeek = $carbonDate->dayOfWeek;

        // Letter rotation: G=Sunday, A=Monday, B=Tuesday, etc.
        $letters = ['G', 'A', 'B', 'C', 'D', 'E', 'F'];
        $letter = $letters[$dayOfWeek] ?? 'A';

        // Pad with leading zeros: 0001, 0002, etc.
        $paddedNumber = str_pad($this->nomor_urut, 4, '0', STR_PAD_LEFT);

        return $letter . $paddedNumber;
    }

    /**
     * Scope for today's queues only
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today()->toDateString());
    }

    /**
     * Get next queue number for today
     */
    public static function getNextQueueNumber()
    {
        $today = now()->toDateString();

        // Get the last queue number for today
        $lastQueue = self::whereDate('tanggal', $today)
            ->orderBy('nomor_urut', 'desc')
            ->first();

        return $lastQueue ? $lastQueue->nomor_urut + 1 : 1;
    }

    /**
     * Generate formatted queue number
     */
    public static function generateQueueNumber($nomorUrut)
    {
        $carbonDate = now();
        $dayOfWeek = $carbonDate->dayOfWeek;

        // Letter rotation: G=Sunday, A=Monday, B=Tuesday, etc.
        $letters = ['G', 'A', 'B', 'C', 'D', 'E', 'F'];
        $letter = $letters[$dayOfWeek] ?? 'A';

        $paddedNumber = str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        return $letter . $paddedNumber;
    }

    /**
     * Boot method to auto-generate queue number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($antrian) {
            if (empty($antrian->nomor_urut)) {
                $antrian->nomor_urut = self::getNextQueueNumber();
            }

            if (empty($antrian->tanggal)) {
                $antrian->tanggal = now()->toDateString();
            }

            if (empty($antrian->nomor_antrian)) {
                $antrian->nomor_antrian = self::generateQueueNumber($antrian->nomor_urut);
            }
        });
    }

    /**
     * Original scopes
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeDipanggil($query)
    {
        return $query->where('status', 'dipanggil');
    }

    public function scopeTerlewat($query)
    {
        return $query->where('status', 'terlewat');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}
