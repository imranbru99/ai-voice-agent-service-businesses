<?php

namespace App\Filament\Resources\CallTranscripts\Pages;

use App\Filament\Resources\CallTranscripts\CallTranscriptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCallTranscripts extends ManageRecords
{
    protected static string $resource = CallTranscriptResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
