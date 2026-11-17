<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'code', 'lead_id', 'voice_call_id', 'service_catalog_item_id', 'assigned_to',
    'title', 'trade', 'status', 'priority', 'window_start', 'window_end',
    'address_line', 'city', 'customer_notes', 'tech_notes', 'quoted_amount',
    'booked_by_ai', 'crm_synced', 'metadata',
])]
class JobBooking extends Model
{
    protected function casts(): array
    {
        return [
            'window_start' => 'datetime',
            'window_end' => 'datetime',
            'quoted_amount' => 'decimal:2',
            'booked_by_ai' => 'boolean',
            'crm_synced' => 'boolean',
            'metadata' => 'array',
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

    public function serviceCatalogItem(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalogItem::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function crmSyncs(): HasMany
    {
        return $this->hasMany(CrmSync::class);
    }
}
