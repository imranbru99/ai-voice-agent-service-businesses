<?php

namespace App\Filament\Resources\VoiceAgentRuns;

use App\Filament\Resources\VoiceAgentRuns\Pages\ManageVoiceAgentRuns;
use App\Models\VoiceAgentRun;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class VoiceAgentRunResource extends Resource
{
    protected static ?string $model = VoiceAgentRun::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|UnitEnum|null $navigationGroup = 'Voice';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Agent Runs';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('voice_agent');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('voice_call_id')->relationship('voiceCall', 'call_sid')->searchable(),
            Select::make('triggered_by')->relationship('triggerer', 'name')->searchable(),
            Select::make('run_type')->options([
                'full_intake' => 'Full intake',
                'after_hours_intake' => 'After-hours intake',
                'qualify_only' => 'Qualify only',
                'book_only' => 'Book only',
            ])->default('full_intake'),
            Select::make('status')->options([
                'queued' => 'Queued',
                'running' => 'Running',
                'completed' => 'Completed',
                'failed' => 'Failed',
            ])->default('queued'),
            TextInput::make('steps')->numeric()->default(0),
            Textarea::make('recommendation')->columnSpanFull(),
            DateTimePicker::make('started_at'),
            DateTimePicker::make('finished_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voiceCall.call_sid')->label('Call')->limit(16),
                TextColumn::make('run_type')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('steps'),
                TextColumn::make('recommendation')->limit(40),
                TextColumn::make('started_at')->dateTime(),
                TextColumn::make('finished_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'queued' => 'Queued',
                    'running' => 'Running',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageVoiceAgentRuns::route('/')];
    }
}
