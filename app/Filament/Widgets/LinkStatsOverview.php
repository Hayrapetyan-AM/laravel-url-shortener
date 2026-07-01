<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use App\Models\LinkClick;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LinkStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->id();

        $linkIds = Link::query()->where('user_id', $userId)->pluck('id');

        $totalClicks = LinkClick::query()
            ->whereIn('link_id', $linkIds)
            ->count();

        return [
            Stat::make('My links', Link::query()->where('user_id', $userId)->count())
                ->description('Links in your account'),
            Stat::make('Total clicks', $totalClicks)
                ->description('Across all your links'),
        ];
    }
}
