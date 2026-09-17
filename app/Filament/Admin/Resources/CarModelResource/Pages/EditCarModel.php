<?php

namespace App\Filament\Admin\Resources\CarModelResource\Pages;

use App\Filament\Admin\Resources\CarModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarModel extends EditRecord
{
    protected static string $resource = CarModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
