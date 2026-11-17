<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'call_sid', 'lead_id', 'handled_by', 'direction', 'from_number', 'to_number',
    'status', 'after_hours', 'answered_by_ai', 'duration_seconds', 'disposition',
    'recording_url', 'ai_summary', 'job_booked', 'lead_qualified', 'crm_synced',
    'started_at', 'ended_at', 'tags',
])]
class VoiceCall extends Model
{
    protected function casts(): array
    {
        return [
            'after_hours' => 'boolean',
            'answered_by_ai' => 'boolean',
            'job_booked' => 'boolean',
            'lead_qualified' => 'boolean',
            'crm_synced' => 'boolean',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'tags' => 'array',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(CallTranscript::class);
    }

    public function jobBookings(): HasMany
    {
        return $this->hasMany(JobBooking::class);
    }

    public function agentRuns(): HasMany
    {
        return $this->hasMany(VoiceAgentRun::class);
    }

    public function crmSyncs(): HasMany
    {
        return $this->hasMany(CrmSync::class);
    }
}
