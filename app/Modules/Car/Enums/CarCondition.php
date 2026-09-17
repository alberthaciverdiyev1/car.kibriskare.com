<?php

namespace App\Modules\Car\Enums;

enum CarCondition: string
{
    case New = 'new';
    case Used = 'used';
    case Damaged = 'damaged';
    case ForParts = 'for_parts';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Yeni (0 km)',
            self::Used => 'İkinci Əl (İşlənmiş)',
            self::Damaged => 'Qəzalı / Zədəli',
            self::ForParts => 'Ehtiyat hissəsi üçün',
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
