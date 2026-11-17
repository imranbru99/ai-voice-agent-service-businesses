<?php

namespace App\Services;

use App\Models\JobBooking;
use App\Models\ServiceCatalogItem;
use App\Models\VoiceCall;
use RuntimeException;

class JobBookingService
{
    public function __construct(protected ModuleManager $modules) {}

    public function bookFromCall(VoiceCall $call): JobBooking
    {
        if (! $this->modules->enabled('job_bookings')) {
            throw new RuntimeException('Job bookings module is disabled.');
        }

        $call->loadMissing('lead');
        $item = ServiceCatalogItem::query()->where('is_active', true)->first();
        $code = 'JOB-'.now()->format('Ymd').'-'.str_pad((string) (JobBooking::query()->count() + 1), 4, '0', STR_PAD_LEFT);

        return JobBooking::query()->updateOrCreate(
            ['voice_call_id' => $call->id],
            [
                'code' => $code,
                'lead_id' => $call->lead_id,
                'service_catalog_item_id' => $item?->id,
                'title' => ($call->lead?->service_interest ?: 'Service call').' — booked by AI',
                'trade' => $item?->trade ?? 'general',
                'status' => 'scheduled',
                'priority' => $call->after_hours ? 'emergency' : 'normal',
                'window_start' => now()->addHours($call->after_hours ? 1 : 4),
                'window_end' => now()->addHours($call->after_hours ? 3 : 6),
                'address_line' => $call->lead?->address_line,
                'city' => $call->lead?->city,
                'customer_notes' => $call->ai_summary,
                'quoted_amount' => $item?->base_price,
                'booked_by_ai' => true,
                'crm_synced' => false,
                'metadata' => ['source' => 'voice_agent'],
            ]
        );
    }
}
