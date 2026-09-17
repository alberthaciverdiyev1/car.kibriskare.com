<?php

namespace App\Modules\Car\Enums;

enum Drivetrain: string
{
    case FrontWheel = 'front_wheel';
    case RearWheel = 'rear_wheel';
    case AllWheel = 'all_wheel';

    public function label(): string
    {
        return match ($this) {
            self::FrontWheel => 'Ön Çəkən (FWD)',
            self::RearWheel => 'Arxa Çəkən (RWD)',
            self::AllWheel => 'Tam Çəkən (4WD / AWD)',
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
