<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Car\Models\Car;
use App\Modules\Location\Models\City;
use Filament\Widgets\ChartWidget;

class CarsByCityChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Elanların Şəhərlərə Görə Bölgüsü';
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $cities = City::withCount('cars')->orderByDesc('cars_count')->limit(6)->get();

        $labels = [];
        $data = [];
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'];

        foreach ($cities as $i => $city) {
            $name = $city->name['tr'] ?? $city->name['az'] ?? ('City #' . $city->id);
            $labels[] = $name . " ({$city->cars_count})";
            $data[] = $city->cars_count;
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
