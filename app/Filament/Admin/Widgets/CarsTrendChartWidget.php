<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Car\Models\Car;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class CarsTrendChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Avtomobil Elanlarının Dinamikası (Son 30 Gün)';
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $days = 30;
        $carData = [];
        $labels = [];

        $now = Carbon::now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $carData[] = Car::whereDate('created_at', $dateString)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yeni Avtomobil Elanları',
                    'data' => $carData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
