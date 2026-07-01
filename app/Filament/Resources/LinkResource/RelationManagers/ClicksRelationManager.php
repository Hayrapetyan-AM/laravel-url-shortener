<?php

namespace App\Filament\Resources\LinkResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    protected static ?string $title = 'All clicks';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return (string) $ownerRecord->clicks()->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicked_at')
                    ->label('Date and time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('clicked_at', 'desc')
            ->paginated([10, 25, 50])
            ->emptyStateHeading('No clicks yet')
            ->emptyStateDescription('Clicks will appear here after someone opens the short link.')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
