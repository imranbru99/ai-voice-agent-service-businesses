<?php

namespace App\Filament\Resources\VoiceAgentRuns\Pages;

use App\Filament\Resources\VoiceAgentRuns\VoiceAgentRunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageVoiceAgentRuns extends ManageRecords
{
    protected static string $resource = VoiceAgentRunResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
