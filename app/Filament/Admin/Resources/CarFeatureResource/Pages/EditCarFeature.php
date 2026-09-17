<?php

namespace App\Filament\Admin\Resources\CarFeatureResource\Pages;

use App\Filament\Admin\Resources\CarFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarFeature extends EditRecord
{
    protected static string $resource = CarFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
