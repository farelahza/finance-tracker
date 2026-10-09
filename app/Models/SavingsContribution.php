<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** savings_goal_id diisi lewat relasi $goal->contributions()->create(...). */
#[Fillable(['amount', 'saved_at', 'note'])]
class SavingsContribution extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'amount'   => 'integer',
            'saved_at' => 'date',
        ];
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(SavingsGoal::class, 'savings_goal_id');
    }
}