<?php

namespace App\Filament\Resources\AfterHoursRules;

use App\Filament\Resources\AfterHoursRules\Pages\ManageAfterHoursRules;
use App\Models\AfterHoursRule;
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

class AfterHoursRuleResource extends Resource
{
    protected static ?string $model = AfterHoursRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMoon;

    protected static string|UnitEnum|null $navigationGroup = 'Voice';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'After-Hours';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('after_hours');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('timezone')->default('America/Chicago')->maxLength(80),
            TextInput::make('business_open')->default('08:00:00')->maxLength(20),
            TextInput::make('business_close')->default('17:00:00')->maxLength(20),
            TextInput::make('escalate_to_phone')->tel()->maxLength(40),
            Textarea::make('greeting_script')->columnSpanFull(),
            Toggle::make('ai_answers')->default(true),
            Toggle::make('allow_emergency_booking')->default(true),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('timezone'),
                TextColumn::make('business_open')->label('Open'),
                TextColumn::make('business_close')->label('Close'),
                IconColumn::make('ai_answers')->boolean(),
                IconColumn::make('allow_emergency_booking')->boolean()->label('Emergency'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAfterHoursRules::route('/')];
    }
}
