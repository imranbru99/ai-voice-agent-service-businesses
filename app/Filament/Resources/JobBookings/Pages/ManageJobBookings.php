<?php

namespace App\Filament\Resources\JobBookings\Pages;

use App\Filament\Resources\JobBookings\JobBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJobBookings extends ManageRecords
{
    protected static string $resource = JobBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
