<?php

namespace App\Filament\Widgets;

use App\Models\JobBooking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class BookingQueueWidget extends TableWidget
{
    protected static ?string $heading = 'Job booking queue';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobBooking::query()->latest('window_start')->limit(8)
            )
            ->columns([
                TextColumn::make('code')->searchable(),
                TextColumn::make('title')->limit(40),
                TextColumn::make('trade')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('window_start')->dateTime()->label('Window'),
            ])
            ->paginated(false);
    }
}
