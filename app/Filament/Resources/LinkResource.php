<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers\ClicksRelationManager;
use App\Models\Link;
use App\Services\LinkService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'My Links';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('original_url')
                    ->label('Original URL')
                    ->url()
                    ->required()
                    ->maxLength(2048)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('short_code')
                    ->label('Short code')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_url')
                    ->label('Short URL')
                    ->state(fn (Link $record): string => $record->shortUrl())
                    ->copyable(),
                Tables\Columns\TextColumn::make('original_url')
                    ->label('Original URL')
                    ->limit(50)
                    ->tooltip(fn (Link $record): string => $record->original_url)
                    ->searchable(),
                Tables\Columns\TextColumn::make('clicks_count')
                    ->label('Total clicks')
                    ->counts('clicks')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Statistics'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->withCount('clicks');
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
