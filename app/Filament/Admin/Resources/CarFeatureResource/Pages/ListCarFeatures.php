<?php

namespace App\Filament\Admin\Resources\CarFeatureResource\Pages;

use App\Filament\Admin\Resources\CarFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarFeatures extends ListRecords
{
    protected static string $resource = CarFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
