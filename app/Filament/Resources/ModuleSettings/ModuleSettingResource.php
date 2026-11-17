<?php

namespace App\Filament\Resources\ModuleSettings;

use App\Filament\Resources\ModuleSettings\Pages\ManageModuleSettings;
use App\Models\ModuleSetting;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ModuleSettingResource extends Resource
{
    protected static ?string $model = ModuleSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 99;

    protected static ?string $navigationLabel = 'Modules';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->disabled()->dehydrated(),
            TextInput::make('name')->required(),
            Textarea::make('description')->columnSpanFull(),
            Toggle::make('enabled')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('description')->limit(50),
                IconColumn::make('enabled')->boolean(),
            ])
            ->recordActions([EditAction::make()])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return ['index' => ManageModuleSettings::route('/')];
    }
}
