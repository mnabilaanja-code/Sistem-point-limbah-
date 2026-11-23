<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function pointRedemptions(): HasMany
    {
        return $this->hasMany(PointRedemption::class);
    }

    /**
     * Total point yang telah disetujui (approved) dari limbah
     */
    public function totalApprovedPoints(): int
    {
        return (int) $this->transactions()->where('status', 'approved')->sum('earned_points');
    }

    /**
     * Total point yang sedang diproses atau sudah sukses ditukar
     */
    public function totalSpentPoints(): int
    {
        return (int) $this->pointRedemptions()->whereIn('status', ['processing', 'completed'])->sum('points_spent');
    }

    /**
     * Saldo point aktif saat ini
     */
    public function availablePoints(): int
    {
        $avail = $this->totalApprovedPoints() - $this->totalSpentPoints();
        return max(0, $avail);
    }

    /**
     * Total rupiah yang sudah berhasil dicairkan (completed)
     */
    public function totalRupiahWithdrawn(): float
    {
        return (float) $this->pointRedemptions()->where('status', 'completed')->sum('rupiah_amount');
    }
}
