<?php

namespace App\Services;

use App\Models\AfterHoursRule;
use Carbon\Carbon;
use RuntimeException;

class AfterHoursService
{
    public function __construct(protected ModuleManager $modules) {}

    public function isAfterHours(?Carbon $at = null): bool
    {
        if (! $this->modules->enabled('after_hours')) {
            return false;
        }

        $rule = AfterHoursRule::query()->where('is_active', true)->first();
        if (! $rule) {
            return false;
        }

        $at = ($at ?? now())->timezone($rule->timezone);
        $weekday = strtolower($at->englishDayOfWeek);
        $weekdays = collect($rule->weekdays ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])
            ->map(fn ($d) => strtolower($d));

        if (! $weekdays->contains($weekday)) {
            return true;
        }

        $open = Carbon::parse($at->toDateString().' '.$rule->business_open, $rule->timezone);
        $close = Carbon::parse($at->toDateString().' '.$rule->business_close, $rule->timezone);

        return $at->lt($open) || $at->gte($close);
    }

    public function activeGreeting(): string
    {
        if (! $this->modules->enabled('after_hours')) {
            throw new RuntimeException('After-hours module is disabled.');
        }

        $rule = AfterHoursRule::query()->where('is_active', true)->first();

        return $rule?->greeting_script
            ?: 'Thanks for calling. Our AI agent can book a job or take a message right now.';
    }
}
