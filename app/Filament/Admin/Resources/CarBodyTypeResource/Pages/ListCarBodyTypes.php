<?php

namespace App\Filament\Admin\Resources\CarBodyTypeResource\Pages;

use App\Filament\Admin\Resources\CarBodyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarBodyTypes extends ListRecords
{
    protected static string $resource = CarBodyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
