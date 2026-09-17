<?php

namespace App\Filament\Admin\Resources\CarBodyTypeResource\Pages;

use App\Filament\Admin\Resources\CarBodyTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarBodyType extends EditRecord
{
    protected static string $resource = CarBodyTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
