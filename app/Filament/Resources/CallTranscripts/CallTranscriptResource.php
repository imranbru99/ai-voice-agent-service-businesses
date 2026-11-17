<?php

namespace App\Filament\Resources\CallTranscripts;

use App\Filament\Resources\CallTranscripts\Pages\ManageCallTranscripts;
use App\Models\CallTranscript;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class CallTranscriptResource extends Resource
{
    protected static ?string $model = CallTranscript::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Voice';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Transcripts';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('call_transcripts');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('voice_call_id')->relationship('voiceCall', 'call_sid')->required()->searchable(),
            TextInput::make('sequence')->numeric()->default(1),
            Select::make('speaker')->options([
                'caller' => 'Caller',
                'agent' => 'AI Agent',
                'human' => 'Human transfer',
            ])->default('caller'),
            TextInput::make('start_ms')->numeric()->default(0),
            TextInput::make('end_ms')->numeric()->default(0),
            Textarea::make('content')->required()->columnSpanFull(),
            TextInput::make('intent')->maxLength(120),
            Select::make('sentiment')->options([
                'positive' => 'Positive',
                'neutral' => 'Neutral',
                'negative' => 'Negative',
                'urgent' => 'Urgent',
            ]),
            Toggle::make('ai_highlighted')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voiceCall.call_sid')->label('Call')->limit(16),
                TextColumn::make('sequence')->sortable(),
                TextColumn::make('speaker')->badge(),
                TextColumn::make('content')->limit(50)->searchable(),
                TextColumn::make('intent')->badge(),
                TextColumn::make('sentiment')->badge(),
                IconColumn::make('ai_highlighted')->boolean()->label('Highlight'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCallTranscripts::route('/')];
    }
}
