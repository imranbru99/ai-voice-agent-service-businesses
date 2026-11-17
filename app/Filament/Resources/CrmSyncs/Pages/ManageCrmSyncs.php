<?php

namespace App\Filament\Resources\CrmSyncs\Pages;

use App\Filament\Resources\CrmSyncs\CrmSyncResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCrmSyncs extends ManageRecords
{
    protected static string $resource = CrmSyncResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
