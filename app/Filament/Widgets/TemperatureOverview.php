<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TemperatureOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 1;
    protected function getStats(): array
    {
        return [

            Stat::make('Fridge Temperature', '-1.2°C')

                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info')
                ->icon('heroicon-o-cube-transparent'),

            Stat::make('Room Temperature', '21.3°C')
                ->chart([5, 15, 3, 7, 10, 2, 15])
                ->color('danger')
                ->icon('heroicon-o-home'),

        ];
    }
}
