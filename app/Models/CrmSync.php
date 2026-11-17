<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'lead_id', 'job_booking_id', 'voice_call_id', 'crm_provider', 'external_id',
    'object_type', 'title', 'status', 'synced_at', 'payload',
])]
class CrmSync extends Model
{
    protected function casts(): array
    {
        return [
            'synced_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function jobBooking(): BelongsTo
    {
        return $this->belongsTo(JobBooking::class);
    }

    public function voiceCall(): BelongsTo
    {
        return $this->belongsTo(VoiceCall::class);
    }
}
