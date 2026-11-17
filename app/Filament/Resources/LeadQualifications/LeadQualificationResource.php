<?php

namespace App\Filament\Resources\LeadQualifications;

use App\Filament\Resources\LeadQualifications\Pages\ManageLeadQualifications;
use App\Models\LeadQualification;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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

class LeadQualificationResource extends Resource
{
    protected static ?string $model = LeadQualification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Revenue';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Qualifications';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('lead_qualification');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lead_id')->relationship('lead', 'name')->required()->searchable(),
            Select::make('voice_call_id')->relationship('voiceCall', 'call_sid')->searchable(),
            TextInput::make('score')->numeric()->required()->minValue(0)->maxValue(100),
            Select::make('urgency')->options([
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'emergency' => 'Emergency',
            ])->default('medium'),
            Select::make('budget_fit')->options([
                'unknown' => 'Unknown',
                'weak' => 'Weak',
                'fair' => 'Fair',
                'strong' => 'Strong',
            ])->default('unknown'),
            Select::make('service_fit')->options([
                'unknown' => 'Unknown',
                'weak' => 'Weak',
                'fair' => 'Fair',
                'strong' => 'Strong',
            ])->default('unknown'),
            Toggle::make('decision_maker')->default(false),
            Textarea::make('notes')->columnSpanFull(),
            DateTimePicker::make('qualified_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.name')->searchable(),
                TextColumn::make('score')->sortable(),
                TextColumn::make('urgency')->badge(),
                TextColumn::make('budget_fit')->badge(),
                TextColumn::make('service_fit')->badge(),
                IconColumn::make('decision_maker')->boolean(),
                TextColumn::make('qualified_at')->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageLeadQualifications::route('/')];
    }
}
