<?php

namespace App\Filament\Widgets;

use App\Models\CrmSync;
use App\Models\JobBooking;
use App\Models\Lead;
use App\Models\VoiceCall;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VoiceCallStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Calls tonight', VoiceCall::query()->whereDate('started_at', today())->count())
                ->description('Inbound & outbound')
                ->color('primary'),
            Stat::make('Jobs booked', JobBooking::query()->where('booked_by_ai', true)->count())
                ->description('Booked by AI voice agent')
                ->color('success'),
            Stat::make('Qualified leads', Lead::query()->where('status', 'qualified')->count())
                ->description('Ready for dispatch / sales')
                ->color('warning'),
            Stat::make('CRM synced', CrmSync::query()->where('status', 'synced')->count())
                ->description('Pushed to connected CRM')
                ->color('info'),
        ];
    }
}
