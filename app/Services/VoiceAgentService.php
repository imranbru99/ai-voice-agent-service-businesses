<?php

namespace App\Services;

use App\Models\VoiceAgentRun;
use App\Models\VoiceCall;
use RuntimeException;

class VoiceAgentService
{
    public function __construct(
        protected ModuleManager $modules,
        protected LeadQualifyService $qualify,
        protected JobBookingService $bookings,
        protected CrmSyncService $crm,
    ) {}

    public function handleInbound(VoiceCall $call, ?int $triggeredBy = null): VoiceAgentRun
    {
        if (! $this->modules->enabled('voice_agent')) {
            throw new RuntimeException('Voice agent module is disabled.');
        }

        $run = VoiceAgentRun::query()->create([
            'voice_call_id' => $call->id,
            'triggered_by' => $triggeredBy,
            'run_type' => $call->after_hours ? 'after_hours_intake' : 'full_intake',
            'status' => 'running',
            'steps' => 0,
            'actions' => [],
            'started_at' => now(),
        ]);

        $actions = [];
        $actions[] = ['step' => 1, 'action' => 'Answer call with branded greeting'];
        $actions[] = ['step' => 2, 'action' => 'Capture caller intent and service interest'];

        if ($this->modules->enabled('lead_qualification') && $call->lead) {
            $this->qualify->qualifyFromCall($call);
            $actions[] = ['step' => 3, 'action' => 'Qualify lead score and urgency'];
            $call->update(['lead_qualified' => true]);
        }

        if ($this->modules->enabled('job_bookings') && $call->disposition === 'booked') {
            $this->bookings->bookFromCall($call);
            $actions[] = ['step' => 4, 'action' => 'Book job window and assign trade'];
            $call->update(['job_booked' => true]);
        }

        if ($this->modules->enabled('crm_sync')) {
            $this->crm->syncCall($call);
            $actions[] = ['step' => 5, 'action' => 'Push lead/job to CRM'];
            $call->update(['crm_synced' => true]);
        }

        $run->update([
            'status' => 'completed',
            'steps' => count($actions),
            'actions' => $actions,
            'recommendation' => $call->after_hours
                ? 'Confirm emergency booking and notify on-call tech within 15 minutes.'
                : 'Review AI summary and confirm CRM owner assignment.',
            'finished_at' => now(),
        ]);

        $call->update([
            'answered_by_ai' => true,
            'status' => 'completed',
            'ai_summary' => $call->ai_summary ?: 'AI answered, qualified intent, and recorded next steps.',
        ]);

        return $run->fresh();
    }
}
