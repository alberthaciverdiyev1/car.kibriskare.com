<?php

namespace App\Filament\Agency\Widgets;

use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use App\Modules\Inquiry\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AgencyStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columns = [
        'sm' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $user = Auth::user();
        $autosalonId = $user?->autosalon?->id ?? $user?->autosalons()->value('id');

        $now = Carbon::now();

        $carQuery = Car::query()->where(function ($q) use ($user, $autosalonId) {
            $q->where('user_id', $user?->id);
            if ($autosalonId) {
                $q->orWhere('autosalon_id', $autosalonId);
            }
        });

        $carIds = (clone $carQuery)->pluck('id');

        // 7-day sparkline data
        $carTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $carTrend[] = (clone $carQuery)->whereDate('created_at', $day)->count();
        }

        $totalCars = $carIds->count();
        $activeCount = (clone $carQuery)->where('status', CarStatus::Active)->count();
        $pendingCount = (clone $carQuery)->where('status', CarStatus::Pending)->count();
        $totalViews = (int) (clone $carQuery)->sum('views_count');

        // Inquiries for these cars
        $inquiriesCount = 0;
        if ($carIds->isNotEmpty()) {
            $inquiriesCount = Inquiry::whereIn('car_id', $carIds)
                ->when($autosalonId, fn ($q) => $q->orWhere('autosalon_id', $autosalonId))
                ->count();
        }

        return [
            Stat::make(__('panel.my_listings') ?: 'İlanlarım', number_format($totalCars))
                ->description('Toplam araç portföyü')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary')
                ->chart($carTrend),

            Stat::make('Yayında Olanlar', number_format($activeCount))
                ->description('Aktif araç ilanları')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('İncelemede Olanlar', number_format($pendingCount))
                ->description($pendingCount > 0 ? 'Onay bekleyen ilanlar' : 'Bekleyen yok')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make('Görüntülenme Sayısı', number_format($totalViews))
                ->description('Toplam ilan görüntülenmesi')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make(__('panel.inquiries') ?: 'Müşteri Talepleri', number_format($inquiriesCount))
                ->description('Gelen müşteri mesajları')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),
        ];
    }
}
