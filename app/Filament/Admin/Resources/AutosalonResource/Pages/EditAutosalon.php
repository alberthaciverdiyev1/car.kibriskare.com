<?php

namespace App\Filament\Admin\Resources\AutosalonResource\Pages;

use App\Filament\Admin\Resources\AutosalonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAutosalon extends EditRecord
{
    protected static string $resource = AutosalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
