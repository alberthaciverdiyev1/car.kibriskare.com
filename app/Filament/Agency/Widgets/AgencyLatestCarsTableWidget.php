<?php

namespace App\Filament\Agency\Widgets;

use App\Filament\Agency\Resources\CarResource;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AgencyLatestCarsTableWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Son Eklenen İlanlarım';
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $autosalonId = $user?->autosalon?->id ?? $user?->autosalons()->value('id');

        $query = Car::query()->where(function ($q) use ($user, $autosalonId) {
            $q->where('user_id', $user?->id);
            if ($autosalonId) {
                $q->orWhere('autosalon_id', $autosalonId);
            }
        });

        return $table
            ->query($query->with(['brand', 'model'])->latest('id')->limit(5))
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Fotoğraf')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(asset('images/car-placeholder.svg')),

                Tables\Columns\TextColumn::make('display_title')
                    ->label('Araç')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Fiyat')
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('year')
                    ->label('Yıl')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('formatted_mileage')
                    ->label('Kilometre'),

                Tables\Columns\TextColumn::make('deal_type')
                    ->label('Tür')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarDealType ? $state->label() : $state),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarStatus ? $state->label() : $state)
                    ->color(fn ($state) => $state instanceof CarStatus ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Görüntülenme')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Düzenle')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Car $record): string => CarResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
