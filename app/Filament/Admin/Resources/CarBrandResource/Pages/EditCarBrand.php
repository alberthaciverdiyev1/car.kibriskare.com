<?php

namespace App\Filament\Admin\Resources\CarBrandResource\Pages;

use App\Filament\Admin\Resources\CarBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarBrand extends EditRecord
{
    protected static string $resource = CarBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
