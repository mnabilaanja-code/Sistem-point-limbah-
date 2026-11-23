<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points_spent',
        'rupiah_amount',
        'status',
        'payment_method',
        'account_details',
        'notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'points_spent' => 'integer',
            'rupiah_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung konversi point ke rupiah sesuai ketentuan baku PRD & Rules:
     * - 20 Point = Rp 1.000 (Rasio: 1 Point = Rp 50)
     * - 10 Point = Rp 500
     * - 1 Point  = Rp 100
     * - Untuk point kelipatan lainnya: points * 50
     */
    public static function calculateRupiah(int $points): float
    {
        if ($points <= 0) {
            return 0.0;
        }

        if ($points === 1) {
            return 100.0;
        }

        if ($points === 10) {
            return 500.0;
        }

        if ($points === 20) {
            return 1000.0;
        }

        // Rasio baku: 1 Point = Rp 50
        return (float) ($points * 50);
    }

    /**
     * Paket standar penukaran point sesuai aturan
     */
    public static function standardPackages(): array
    {
        return [
            ['points' => 1, 'rupiah' => 100, 'label' => 'Paket Mikro: 1 Point = Rp 100'],
            ['points' => 10, 'rupiah' => 500, 'label' => 'Paket Mini: 10 Point = Rp 500'],
            ['points' => 20, 'rupiah' => 1000, 'label' => 'Paket Reguler: 20 Point = Rp 1.000'],
            ['points' => 100, 'rupiah' => 5000, 'label' => 'Paket Hemat: 100 Point = Rp 5.000'],
            ['points' => 200, 'rupiah' => 10000, 'label' => 'Paket Mantap: 200 Point = Rp 10.000'],
            ['points' => 500, 'rupiah' => 25000, 'label' => 'Paket Super: 500 Point = Rp 25.000'],
            ['points' => 1000, 'rupiah' => 50000, 'label' => 'Paket Sultan: 1.000 Point = Rp 50.000'],
        ];
    }
}
