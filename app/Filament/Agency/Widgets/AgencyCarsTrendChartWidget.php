<?php

namespace App\Filament\Agency\Widgets;

use App\Modules\Car\Models\Car;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AgencyCarsTrendChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '230px';
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function getHeading(): ?string
    {
        return 'Portföy İlan İstatistiği (Son 30 Gün)';
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $autosalonId = $user?->autosalon?->id ?? $user?->autosalons()->value('id');

        $carQuery = Car::query()->where(function ($q) use ($user, $autosalonId) {
            $q->where('user_id', $user?->id);
            if ($autosalonId) {
                $q->orWhere('autosalon_id', $autosalonId);
            }
        });

        $days = 30;
        $carData = [];
        $labels = [];

        $now = Carbon::now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $carData[] = (clone $carQuery)->whereDate('created_at', $dateString)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Eklenen İlanlar',
                    'data' => $carData,
                    'borderColor' => '#ea580c',
                    'backgroundColor' => 'rgba(234, 88, 12, 0.15)',
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
