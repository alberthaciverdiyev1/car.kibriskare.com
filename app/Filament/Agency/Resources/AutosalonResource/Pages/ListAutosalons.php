<?php

namespace App\Filament\Agency\Resources\AutosalonResource\Pages;

use App\Filament\Agency\Resources\AutosalonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListAutosalons extends ListRecords
{
    protected static string $resource = AutosalonResource::class;

    protected function getHeaderActions(): array
    {
        $hasSalon = Auth::user()?->autosalons()->exists();
        if ($hasSalon) {
            return [];
        }

        return [
            Actions\CreateAction::make()->label('Avtosalon Profili Yarat'),
        ];
    }
}
