<?php

namespace App\Filament\Agency\Resources\CarResource\Pages;

use App\Filament\Agency\Resources\CarResource;
use App\Modules\Car\Enums\CarStatus;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateCar extends CreateRecord
{
    protected static string $resource = CarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['autosalon_id'] = $user->autosalon?->id ?? $user->autosalons()->value('id');
        $data['status'] = CarStatus::Active->value;

        if (empty($data['slug'])) {
            $brandName = \App\Modules\Car\Models\CarBrand::find($data['brand_id'])?->name ?? 'car';
            $modelName = \App\Modules\Car\Models\CarModel::find($data['model_id'])?->name ?? 'model';
            $data['slug'] = Str::slug("{$brandName}-{$modelName}-{$data['year']}-" . Str::random(5));
        }

        return $data;
    }
}
