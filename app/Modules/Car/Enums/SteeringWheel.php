<?php

namespace App\Modules\Car\Enums;

enum SteeringWheel: string
{
    case Right = 'right';
    case Left = 'left';

    public function label(): string
    {
        return match ($this) {
            self::Right => 'Sağ Sükan (KKTC / UK)',
            self::Left => 'Sol Sükan',
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
