<?php

namespace App\Filament\Resources\LeadQualifications\Pages;

use App\Filament\Resources\LeadQualifications\LeadQualificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLeadQualifications extends ManageRecords
{
    protected static string $resource = LeadQualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
