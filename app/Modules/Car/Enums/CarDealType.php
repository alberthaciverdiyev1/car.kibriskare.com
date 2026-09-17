<?php

namespace App\Modules\Car\Enums;

enum CarDealType: string
{
    case Sale = 'sale';
    case RentDaily = 'rent_daily';
    case RentMonthly = 'rent_monthly';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'Satılıq',
            self::RentDaily => 'Kirayə',
            self::RentMonthly => 'Aylıq Kirayə',
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
