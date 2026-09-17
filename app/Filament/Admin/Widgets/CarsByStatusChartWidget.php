<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use Filament\Widgets\ChartWidget;

class CarsByStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Elanların Statusa Görə Bölgüsü';
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $statuses = [
            CarStatus::Active->value => ['label' => 'Aktiv', 'color' => '#10b981'],
            CarStatus::Pending->value => ['label' => 'Təsdiq gözləyir', 'color' => '#f59e0b'],
            CarStatus::Draft->value => ['label' => 'Qaralama', 'color' => '#6b7280'],
            CarStatus::Rejected->value => ['label' => 'İmtina edilib', 'color' => '#ef4444'],
            CarStatus::Sold->value => ['label' => 'Satılıb', 'color' => '#3b82f6'],
            CarStatus::Archived->value => ['label' => 'Arxivlənib', 'color' => '#94a3b8'],
        ];

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($statuses as $statusKey => $meta) {
            $count = Car::where('status', $statusKey)->count();
            if ($count > 0 || in_array($statusKey, [CarStatus::Active->value, CarStatus::Pending->value])) {
                $labels[] = $meta['label'] . " ({$count})";
                $data[] = $count;
                $colors[] = $meta['color'];
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 2,
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
