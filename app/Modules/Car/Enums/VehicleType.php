<?php

namespace App\Modules\Car\Enums;

enum VehicleType: string
{
    case Car = 'car';
    case Suv = 'suv';
    case Motorcycle = 'motorcycle';
    case Commercial = 'commercial';
    case Classic = 'classic';
    case Damaged = 'damaged';

    public function label(): string
    {
        return match ($this) {
            self::Car => __('Otomobil'),
            self::Suv => __('Arazi, SUV & Pick-up'),
            self::Motorcycle => __('Motosiklet & Skuter'),
            self::Commercial => __('Ticari Araçlar'),
            self::Classic => __('Klasik Araçlar'),
            self::Damaged => __('Hasarlı & Parçalık'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
