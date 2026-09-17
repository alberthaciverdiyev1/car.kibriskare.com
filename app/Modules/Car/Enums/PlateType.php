<?php

namespace App\Modules\Car\Enums;

enum PlateType: string
{
    case Kktc = 'kktc';
    case Foreign = 'foreign';
    case ZPlate = 'z_plate';
    case TPlate = 't_plate';

    public function label(): string
    {
        return match ($this) {
            self::Kktc => __('KKTC Plakalı (Gümrüğü Ödenmiş)'),
            self::Foreign => __('Yurtdışı Plakalı (Gümrüksüz)'),
            self::ZPlate => __('Z Plaka (Rent a Car)'),
            self::TPlate => __('T Plaka (Ticari / Taksi)'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
