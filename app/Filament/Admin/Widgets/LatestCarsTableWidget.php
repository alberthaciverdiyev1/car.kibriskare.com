<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\CarResource;
use App\Modules\Car\Enums\CarDealType;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestCarsTableWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Son Əlavə Edilmiş Avtomobil Elanları';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Car::query()->with(['brand', 'model', 'city', 'autosalon'])->latest('id')->limit(8)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images.image_path')
                    ->label('Şəkil')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(asset('images/car-placeholder.svg')),

                Tables\Columns\TextColumn::make('display_title')
                    ->label('Avtomobil')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('formatted_price')
                    ->label('Qiymət')
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('year')
                    ->label('İl')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('formatted_mileage')
                    ->label('Yürüş'),

                Tables\Columns\TextColumn::make('deal_type')
                    ->label('Növ')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarDealType ? $state->label() : $state),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof CarStatus ? $state->label() : $state)
                    ->color(fn ($state) => $state instanceof CarStatus ? $state->color() : 'gray'),

                Tables\Columns\TextColumn::make('city.name.tr')
                    ->label('Şəhər')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarix')
                    ->dateTime('d.m.Y H:i')
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Düzəliş et')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Car $record): string => CarResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
