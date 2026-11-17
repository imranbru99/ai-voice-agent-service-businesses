<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\ManageLeads;
use App\Models\Lead;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Revenue';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Leads';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('leads');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required()->unique(ignoreRecord: true)->maxLength(40),
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('phone')->tel()->maxLength(40),
            TextInput::make('email')->email()->maxLength(255),
            TextInput::make('company')->maxLength(255),
            Select::make('source')->options([
                'inbound_call' => 'Inbound call',
                'outbound_call' => 'Outbound call',
                'web_form' => 'Web form',
                'referral' => 'Referral',
            ])->default('inbound_call'),
            Select::make('status')->options([
                'new' => 'New',
                'qualified' => 'Qualified',
                'nurture' => 'Nurture',
                'won' => 'Won',
                'lost' => 'Lost',
            ])->default('new'),
            Select::make('owner_id')->relationship('owner', 'name')->searchable(),
            TextInput::make('service_interest')->maxLength(255),
            TextInput::make('address_line')->maxLength(255),
            TextInput::make('city')->maxLength(120),
            TextInput::make('region')->maxLength(120),
            TextInput::make('score')->numeric()->default(0)->minValue(0)->maxValue(100),
            Toggle::make('crm_synced')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('service_interest')->limit(24),
                TextColumn::make('status')->badge(),
                TextColumn::make('score')->sortable(),
                TextColumn::make('owner.name')->label('Owner'),
                IconColumn::make('crm_synced')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'new' => 'New',
                    'qualified' => 'Qualified',
                    'nurture' => 'Nurture',
                    'won' => 'Won',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageLeads::route('/')];
    }
}
