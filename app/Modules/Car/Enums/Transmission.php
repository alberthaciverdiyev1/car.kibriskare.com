<?php

namespace App\Modules\Car\Enums;

enum Transmission: string
{
    case Automatic = 'automatic';
    case Manual = 'manual';
    case Robot = 'robot';
    case Cvt = 'cvt';

    public function label(): string
    {
        return match ($this) {
            self::Automatic => 'Avtomat',
            self::Manual => 'Mexaniki',
            self::Robot => 'Robotlaşdırılmış',
            self::Cvt => 'Variator (CVT)',
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
