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
            self::FrontWheel => __('Önden Çekiş (FWD)'),
            self::RearWheel => __('Arkadan İtiş (RWD)'),
            self::AllWheel => __('4 Çeker (AWD / 4WD)'),
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
