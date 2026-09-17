<?php

namespace App\Modules\Car\Enums;

enum FuelType: string
{
    case Petrol = 'petrol';
    case Diesel = 'diesel';
    case Hybrid = 'hybrid';
    case PlugInHybrid = 'plug_in_hybrid';
    case Electric = 'electric';
    case Lpg = 'lpg';

    public function label(): string
    {
        return match ($this) {
            self::Petrol => 'Benzin',
            self::Diesel => 'Dizel',
            self::Hybrid => 'Hibrid',
            self::PlugInHybrid => 'Plug-in Hibrid',
            self::Electric => 'Elektrik',
            self::Lpg => 'Qaz (LPG)',
        };
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases()), 'label', 'value');
    }
}
