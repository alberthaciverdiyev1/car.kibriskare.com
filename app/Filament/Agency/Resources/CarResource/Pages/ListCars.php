<?php

namespace App\Filament\Agency\Resources\CarResource\Pages;

use App\Filament\Agency\Resources\CarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCars extends ListRecords
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Yeni Avtomobil Elanı Əlavə Et'),
        ];
    }
}
