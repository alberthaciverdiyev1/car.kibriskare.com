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
            self::Petrol => __('Benzin'),
            self::Diesel => __('Dizel'),
            self::Hybrid => __('Hibrit'),
            self::PlugInHybrid => __('Plug-in Hibrit'),
            self::Electric => __('Elektrik'),
            self::Lpg => __('LPG & Benzin'),
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
