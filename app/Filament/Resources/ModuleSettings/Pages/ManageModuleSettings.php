<?php

namespace App\Filament\Resources\ModuleSettings\Pages;

use App\Filament\Resources\ModuleSettings\ModuleSettingResource;
use App\Services\ModuleManager;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Cache;

class ManageModuleSettings extends ManageRecords
{
    protected static string $resource = ModuleSettingResource::class;

    protected function afterSave(): void
    {
        Cache::forget('module_settings_map');
        app(ModuleManager::class)->all();
    }
}
