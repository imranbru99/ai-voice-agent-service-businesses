<?php

namespace App\Filament\Resources\CrmSyncs;

use App\Filament\Resources\CrmSyncs\Pages\ManageCrmSyncs;
use App\Models\CrmSync;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CrmSyncResource extends Resource
{
    protected static ?string $model = CrmSync::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPathRoundedSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Integrations';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'CRM Sync';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('crm_sync');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lead_id')->relationship('lead', 'name')->searchable(),
            Select::make('job_booking_id')->relationship('jobBooking', 'code')->searchable(),
            Select::make('voice_call_id')->relationship('voiceCall', 'call_sid')->searchable(),
            Select::make('crm_provider')->options([
                'hubspot' => 'HubSpot',
                'jobber' => 'Jobber',
                'servicetitan' => 'ServiceTitan',
                'salesforce' => 'Salesforce',
            ])->default('hubspot'),
            TextInput::make('external_id')->maxLength(120),
            Select::make('object_type')->options([
                'lead' => 'Lead',
                'job' => 'Job',
                'contact' => 'Contact',
            ])->default('lead'),
            TextInput::make('title')->required()->maxLength(255),
            Select::make('status')->options([
                'pending' => 'Pending',
                'synced' => 'Synced',
                'failed' => 'Failed',
            ])->default('pending'),
            DateTimePicker::make('synced_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('crm_provider')->badge(),
                TextColumn::make('object_type')->badge(),
                TextColumn::make('external_id')->limit(20),
                TextColumn::make('status')->badge(),
                TextColumn::make('synced_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'synced' => 'Synced',
                    'failed' => 'Failed',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCrmSyncs::route('/')];
    }
}
