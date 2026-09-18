<?php

namespace App\Modules\Car\Enums;

enum SteeringWheel: string
{
    case Right = 'right';
    case Left = 'left';

    public function label(): string
    {
        return match ($this) {
            self::Right => __('Sağ Direksiyon (KKTC / UK)'),
            self::Left => __('Sol Direksiyon'),
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
