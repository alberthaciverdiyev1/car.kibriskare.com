<?php

namespace App\Filament\Agency\Widgets;

use App\Modules\Car\Models\Car;
use App\Modules\Inquiry\Models\Inquiry;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AgencyLatestInquiriesTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'xl' => 2,
    ];

    public function getHeading(): ?string
    {
        return 'Son Müşteri Talepleri ve Mesajları';
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $autosalonId = $user?->autosalon?->id ?? $user?->autosalons()->value('id');

        $carIds = Car::where(function ($q) use ($user, $autosalonId) {
            $q->where('user_id', $user?->id);
            if ($autosalonId) {
                $q->orWhere('autosalon_id', $autosalonId);
            }
        })->pluck('id');

        return $table
            ->query(
                Inquiry::query()
                    ->with(['car.brand', 'car.model'])
                    ->where(function ($q) use ($carIds, $autosalonId) {
                        if ($carIds->isNotEmpty()) {
                            $q->whereIn('car_id', $carIds);
                        }
                        if ($autosalonId) {
                            $q->orWhere('autosalon_id', $autosalonId);
                        }
                    })
                    ->latest('id')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Müşteri')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('car.display_title')
                    ->label('İlgili Araç')
                    ->limit(25)
                    ->placeholder('Genel Talep'),

                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(35),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->since(),
            ])
            ->emptyStateHeading('Henüz müşteri talebi bulunmuyor')
            ->emptyStateDescription('İlanlarınızdan gelen mesajlar burada listelenecektir.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
