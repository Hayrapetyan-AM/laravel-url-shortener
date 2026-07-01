<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use App\Models\Link;
use Filament\Actions;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewLink extends ViewRecord
{
    protected static string $resource = LinkResource::class;

    public function getTitle(): string
    {
        return 'Link statistics';
    }

    protected function resolveRecord(int|string $key): Model
    {
        /** @var Link $record */
        $record = parent::resolveRecord($key);

        return $record
            ->loadCount('clicks')
            ->load(['clicks' => fn ($query) => $query->latest('clicked_at')]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Link')
                    ->schema([
                        TextEntry::make('short_code')
                            ->label('Short code'),
                        TextEntry::make('short_url')
                            ->label('Short URL')
                            ->state(fn (Link $record): string => $record->shortUrl())
                            ->copyable(),
                        TextEntry::make('original_url')
                            ->label('Original URL')
                            ->copyable()
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(2),
                Section::make('Click statistics')
                    ->schema([
                        TextEntry::make('clicks_count')
                            ->label('Total clicks')
                            ->badge()
                            ->color('success'),
                        RepeatableEntry::make('clicks')
                            ->label('Click history')
                            ->schema([
                                TextEntry::make('ip_address')
                                    ->label('IP address'),
                                TextEntry::make('clicked_at')
                                    ->label('Clicked at')
                                    ->dateTime(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->placeholder('No clicks recorded yet.'),
                    ]),
            ]);
    }
}
