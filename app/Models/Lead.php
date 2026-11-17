<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'name', 'phone', 'email', 'company', 'source', 'status', 'owner_id',
    'service_interest', 'address_line', 'city', 'region', 'score', 'crm_synced', 'metadata',
])]
class Lead extends Model
{
    protected function casts(): array
    {
        return [
            'crm_synced' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function voiceCalls(): HasMany
    {
        return $this->hasMany(VoiceCall::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(LeadQualification::class);
    }

    public function jobBookings(): HasMany
    {
        return $this->hasMany(JobBooking::class);
    }

    public function crmSyncs(): HasMany
    {
        return $this->hasMany(CrmSync::class);
    }
}
