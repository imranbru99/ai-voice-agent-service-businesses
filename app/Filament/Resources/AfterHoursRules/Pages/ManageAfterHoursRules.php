<?php

namespace App\Filament\Resources\AfterHoursRules\Pages;

use App\Filament\Resources\AfterHoursRules\AfterHoursRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAfterHoursRules extends ManageRecords
{
    protected static string $resource = AfterHoursRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
