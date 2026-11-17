<?php

namespace App\Filament\Resources\ServiceCatalogItems;

use App\Filament\Resources\ServiceCatalogItems\Pages\ManageServiceCatalogItems;
use App\Models\ServiceCatalogItem;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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

class ServiceCatalogItemResource extends Resource
{
    protected static ?string $model = ServiceCatalogItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Service Catalog';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('service_catalog');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sku')->required()->unique(ignoreRecord: true)->maxLength(40),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('trade')->default('general')->maxLength(80),
            TextInput::make('slug')->maxLength(255),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('base_price')->numeric()->default(0),
            TextInput::make('typical_duration_minutes')->numeric()->default(60),
            Toggle::make('bookable_after_hours')->default(true),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('trade')->badge(),
                TextColumn::make('base_price')->money('USD'),
                TextColumn::make('typical_duration_minutes')->label('Mins'),
                IconColumn::make('bookable_after_hours')->boolean()->label('After-hrs'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageServiceCatalogItems::route('/')];
    }
}
