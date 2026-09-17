<?php

namespace App\Filament\Agency\Resources\AutosalonResource\Pages;

use App\Filament\Agency\Resources\AutosalonResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAutosalon extends CreateRecord
{
    protected static string $resource = AutosalonResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['is_active'] = true;
        return $data;
    }
}
