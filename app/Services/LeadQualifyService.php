<?php

namespace App\Services;

use App\Models\LeadQualification;
use App\Models\VoiceCall;
use RuntimeException;

class LeadQualifyService
{
    public function __construct(protected ModuleManager $modules) {}

    public function qualifyFromCall(VoiceCall $call): LeadQualification
    {
        if (! $this->modules->enabled('lead_qualification')) {
            throw new RuntimeException('Lead qualification module is disabled.');
        }

        $call->loadMissing('lead');
        $score = min(100, 55 + ($call->after_hours ? 20 : 0) + ($call->duration_seconds > 90 ? 15 : 5));

        $qualification = LeadQualification::query()->updateOrCreate(
            [
                'lead_id' => $call->lead_id,
                'voice_call_id' => $call->id,
            ],
            [
                'score' => $score,
                'urgency' => $call->after_hours ? 'high' : 'medium',
                'budget_fit' => $score >= 75 ? 'strong' : 'unknown',
                'service_fit' => 'strong',
                'decision_maker' => $score >= 70,
                'notes' => 'Qualified from live voice agent conversation.',
                'signals' => [
                    'after_hours' => $call->after_hours,
                    'duration_seconds' => $call->duration_seconds,
                    'disposition' => $call->disposition,
                ],
                'qualified_at' => now(),
            ]
        );

        if ($call->lead) {
            $call->lead->update([
                'score' => $score,
                'status' => $score >= 70 ? 'qualified' : 'nurture',
            ]);
        }

        return $qualification;
    }
}
