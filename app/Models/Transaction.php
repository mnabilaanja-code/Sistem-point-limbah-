<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waste_type_id',
        'quality_grade_id',
        'quantity',
        'earned_points',
        'status',
        'notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'earned_points' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }

    public function qualityGrade(): BelongsTo
    {
        return $this->belongsTo(QualityGrade::class);
    }

    /**
     * Hitung perolehan point berdasarkan jenis limbah, grade kualitas, dan kuantitas.
     * Sesuai ketentuan PRD:
     * - Botol: 1 Botol = 1 Point dasar
     * - Kaleng: 100 gram = 1 Point dasar
     * Dikalikan bobot Grade (A: 4, B: 3, C: 2, D: 1)
     */
    public static function calculateEarnedPoints(WasteType $wasteType, QualityGrade $grade, float $quantity): int
    {
        $baseConversion = max(1, (int) $wasteType->base_point_conversion);
        $multiplier = max(1, (int) $grade->point_multiplier);

        // Nilai dasar dikalikan multiplier grade
        $points = ($quantity / $baseConversion) * $multiplier;

        return (int) round($points);
    }
}
