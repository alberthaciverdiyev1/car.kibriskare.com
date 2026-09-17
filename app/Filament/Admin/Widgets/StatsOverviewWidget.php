<?php

namespace App\Filament\Admin\Widgets;

use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Inquiry\Models\Inquiry;
use App\Modules\Shared\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columns = [
        'sm' => 2,
        'lg' => 4,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $now = Carbon::now();

        // 7-day sparkline data
        $carTrend = [];
        $userTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->format('Y-m-d');
            $carTrend[] = Car::whereDate('created_at', $day)->count();
            $userTrend[] = User::whereDate('created_at', $day)->count();
        }

        $totalCars = Car::count();
        $activeCount = Car::where('status', CarStatus::Active)->count();
        $pendingCount = Car::where('status', CarStatus::Pending)->count();
        $totalViews = (int) Car::sum('views_count');

        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', $now->copy()->subDays(7))->count();

        $autosalonCount = Autosalon::count();
        $inquiryCount = Inquiry::count();

        return [
            Stat::make('Ümumi Avtomobil', number_format($totalCars))
                ->description("Son 7 gündə: +" . array_sum($carTrend))
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary')
                ->chart($carTrend),

            Stat::make('Aktiv Elanlar', number_format($activeCount))
                ->description('Saytda dərc edilmiş elanlar')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Təsdiq Gözləyən', number_format($pendingCount))
                ->description($pendingCount > 0 ? 'Moderasiya tələb olunur' : 'Yoxlanılıb')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'gray'),

            Stat::make('İstifadəçilər', number_format($totalUsers))
                ->description("+{$newUsersThisWeek} yeni (bu həftə)")
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart($userTrend),

            Stat::make('Avtosalonlar (Qalereyalar)', number_format($autosalonCount))
                ->description('Qeydiyyatlı rəsmi dilerlər')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Müştəri Müraciətləri', number_format($inquiryCount))
                ->description('Gələn mesaj & sorğular')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('danger'),

            Stat::make('Ümumi Baxış Sayı', number_format($totalViews))
                ->description('Bütün elanların baxış cəmi')
                ->descriptionIcon('heroicon-m-eye')
                ->color('gray'),
        ];
    }
}

