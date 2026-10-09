<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * user_id SENGAJA tidak ada di Fillable: selalu diisi dari user yang login,
 * tidak pernah dari input form.
 */
#[Fillable(['name', 'target_amount', 'description', 'deadline'])]
class SavingsGoal extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'target_amount' => 'integer',
            'deadline'      => 'date',
        ];
    }

    /* ---------- Relasi ---------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(SavingsContribution::class);
    }

    /* ---------- Scope ---------- */

    public function scopeOwnedBy(Builder $query, int|string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /* ---------- Nilai turunan (dihitung dari riwayat, tidak disimpan) ---------- */

    /**
     * Total progres. Memakai hasil withSum()/loadSum() bila sudah dimuat
     * (menghindari query berulang), jika belum maka dihitung dari database.
     */
    public function getSavedAmountAttribute(): int
    {
        if (array_key_exists('contributions_sum_amount', $this->attributes)) {
            return (int) $this->attributes['contributions_sum_amount'];
        }

        return (int) $this->contributions()->sum('amount');
    }

    public function getIsAchievedAttribute(): bool
    {
        return $this->target_amount > 0 && $this->saved_amount >= $this->target_amount;
    }

    /** Sisa yang dibutuhkan. Tidak pernah negatif. */
    public function getRemainingAmountAttribute(): int
    {
        return max(0, $this->target_amount - $this->saved_amount);
    }

    /** Kelebihan tabungan di atas target (0 jika belum melewati target). */
    public function getSurplusAmountAttribute(): int
    {
        return max(0, $this->saved_amount - $this->target_amount);
    }

    /**
     * Persentase bulat 0-100. Dibulatkan ke BAWAH, sehingga 99,9% tidak
     * tampil 100% selama target belum benar-benar tercapai.
     */
    public function getProgressPercentAttribute(): int
    {
        if ($this->is_achieved) {
            return 100;
        }

        if ($this->target_amount <= 0) {
            return 0;
        }

        return (int) floor(($this->saved_amount * 100) / $this->target_amount);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_achieved ? 'Tercapai' : 'Berjalan';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline !== null
            && ! $this->is_achieved
            && $this->deadline->isBefore(now()->startOfDay());
    }
}