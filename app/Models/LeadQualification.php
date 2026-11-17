<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'lead_id', 'voice_call_id', 'score', 'urgency', 'budget_fit', 'service_fit',
    'decision_maker', 'notes', 'signals', 'qualified_at',
])]
class LeadQualification extends Model
{
    protected function casts(): array
    {
        return [
            'decision_maker' => 'boolean',
            'signals' => 'array',
            'qualified_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function voiceCall(): BelongsTo
    {
        return $this->belongsTo(VoiceCall::class);
    }
}
