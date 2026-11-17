<?php

namespace App\Filament\Resources\ServiceCatalogItems\Pages;

use App\Filament\Resources\ServiceCatalogItems\ServiceCatalogItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageServiceCatalogItems extends ManageRecords
{
    protected static string $resource = ServiceCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
