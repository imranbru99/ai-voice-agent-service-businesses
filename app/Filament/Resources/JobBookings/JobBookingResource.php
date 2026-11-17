<?php

namespace App\Filament\Resources\JobBookings;

use App\Filament\Resources\JobBookings\Pages\ManageJobBookings;
use App\Models\JobBooking;
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

class JobBookingResource extends Resource
{
    protected static ?string $model = JobBooking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Job Bookings';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('job_bookings');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required()->unique(ignoreRecord: true)->maxLength(40),
            Select::make('lead_id')->relationship('lead', 'name')->searchable(),
            Select::make('voice_call_id')->relationship('voiceCall', 'call_sid')->searchable(),
            Select::make('service_catalog_item_id')->relationship('serviceCatalogItem', 'name')->searchable(),
            Select::make('assigned_to')->relationship('assignee', 'name')->searchable(),
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('trade')->default('general')->maxLength(80),
            Select::make('status')->options([
                'scheduled' => 'Scheduled',
                'dispatched' => 'Dispatched',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->default('scheduled'),
            Select::make('priority')->options([
                'low' => 'Low',
                'normal' => 'Normal',
                'high' => 'High',
                'emergency' => 'Emergency',
            ])->default('normal'),
            DateTimePicker::make('window_start'),
            DateTimePicker::make('window_end'),
            TextInput::make('address_line')->maxLength(255),
            TextInput::make('city')->maxLength(120),
            TextInput::make('quoted_amount')->numeric(),
            Textarea::make('customer_notes')->columnSpanFull(),
            Textarea::make('tech_notes')->columnSpanFull(),
            Toggle::make('booked_by_ai')->default(true),
            Toggle::make('crm_synced')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('title')->limit(36)->searchable(),
                TextColumn::make('trade')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('assignee.name')->label('Tech'),
                TextColumn::make('window_start')->dateTime()->sortable(),
                IconColumn::make('booked_by_ai')->boolean()->label('AI'),
                IconColumn::make('crm_synced')->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'scheduled' => 'Scheduled',
                    'dispatched' => 'Dispatched',
                    'completed' => 'Completed',
                ]),
                SelectFilter::make('priority')->options([
                    'normal' => 'Normal',
                    'high' => 'High',
                    'emergency' => 'Emergency',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageJobBookings::route('/')];
    }
}
