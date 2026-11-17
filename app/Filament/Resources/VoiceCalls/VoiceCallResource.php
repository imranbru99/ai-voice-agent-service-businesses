<?php

namespace App\Filament\Resources\VoiceCalls;

use App\Filament\Resources\VoiceCalls\Pages\ManageVoiceCalls;
use App\Models\VoiceCall;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class VoiceCallResource extends Resource
{
    protected static ?string $model = VoiceCall::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|UnitEnum|null $navigationGroup = 'Voice';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Voice Calls';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('voice_calls');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('call_sid')->required()->unique(ignoreRecord: true)->maxLength(80),
            Select::make('lead_id')->relationship('lead', 'name')->searchable(),
            Select::make('handled_by')->relationship('handler', 'name')->searchable(),
            Select::make('direction')->options([
                'inbound' => 'Inbound',
                'outbound' => 'Outbound',
            ])->required()->default('inbound'),
            TextInput::make('from_number')->required()->maxLength(40),
            TextInput::make('to_number')->maxLength(40),
            Select::make('status')->options([
                'ringing' => 'Ringing',
                'in_progress' => 'In progress',
                'completed' => 'Completed',
                'missed' => 'Missed',
                'failed' => 'Failed',
            ])->required()->default('ringing'),
            Select::make('disposition')->options([
                'booked' => 'Booked',
                'qualified' => 'Qualified',
                'callback' => 'Callback',
                'spam' => 'Spam',
                'voicemail' => 'Voicemail',
            ]),
            TextInput::make('duration_seconds')->numeric()->default(0),
            TextInput::make('recording_url')->url()->maxLength(255),
            Textarea::make('ai_summary')->columnSpanFull(),
            DateTimePicker::make('started_at'),
            DateTimePicker::make('ended_at'),
            Toggle::make('after_hours')->default(false),
            Toggle::make('answered_by_ai')->default(true),
            Toggle::make('job_booked')->default(false),
            Toggle::make('lead_qualified')->default(false),
            Toggle::make('crm_synced')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('call_sid')->searchable()->limit(18),
                TextColumn::make('from_number')->searchable(),
                TextColumn::make('lead.name')->label('Caller'),
                TextColumn::make('direction')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('disposition')->badge(),
                IconColumn::make('after_hours')->boolean(),
                IconColumn::make('answered_by_ai')->boolean()->label('AI'),
                IconColumn::make('job_booked')->boolean(),
                IconColumn::make('crm_synced')->boolean(),
                TextColumn::make('started_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'ringing' => 'Ringing',
                    'in_progress' => 'In progress',
                    'completed' => 'Completed',
                    'missed' => 'Missed',
                ]),
                SelectFilter::make('disposition')->options([
                    'booked' => 'Booked',
                    'qualified' => 'Qualified',
                    'callback' => 'Callback',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageVoiceCalls::route('/')];
    }
}
