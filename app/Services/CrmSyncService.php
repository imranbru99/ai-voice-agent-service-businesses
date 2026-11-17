<?php

namespace App\Services;

use App\Models\CrmSync;
use App\Models\VoiceCall;
use RuntimeException;

class CrmSyncService
{
    public function __construct(protected ModuleManager $modules) {}

    public function syncCall(VoiceCall $call): CrmSync
    {
        if (! $this->modules->enabled('crm_sync')) {
            throw new RuntimeException('CRM sync module is disabled.');
        }

        $call->loadMissing(['lead', 'jobBookings']);
        $booking = $call->jobBookings()->latest('id')->first();

        $sync = CrmSync::query()->updateOrCreate(
            [
                'voice_call_id' => $call->id,
                'object_type' => $booking ? 'job' : 'lead',
            ],
            [
                'lead_id' => $call->lead_id,
                'job_booking_id' => $booking?->id,
                'crm_provider' => 'hubspot',
                'external_id' => 'VS-'.$call->call_sid,
                'title' => $booking?->title ?? ($call->lead?->name.' inbound lead'),
                'status' => 'synced',
                'synced_at' => now(),
                'payload' => [
                    'after_hours' => $call->after_hours,
                    'disposition' => $call->disposition,
                    'qualified' => $call->lead_qualified,
                ],
            ]
        );

        $call->lead?->update(['crm_synced' => true]);
        $booking?->update(['crm_synced' => true]);

        return $sync;
    }
}
