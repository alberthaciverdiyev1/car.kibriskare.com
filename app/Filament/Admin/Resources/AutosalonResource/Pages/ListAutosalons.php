<?php

namespace App\Filament\Admin\Resources\AutosalonResource\Pages;

use App\Filament\Admin\Resources\AutosalonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutosalons extends ListRecords
{
    protected static string $resource = AutosalonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
