<?php

namespace App\Modules\Car\Enums;

enum CarStatus: string
{
    case Active = 'active';
    case Pending = 'pending';
    case Rejected = 'rejected';
    case Sold = 'sold';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktiv',
            self::Pending => 'Gözləmədə (Moderasiya)',
            self::Rejected => 'İmtina edilmiş',
            self::Sold => 'Satıldı',
            self::Inactive => 'Deaktiv',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Pending => 'warning',
            self::Rejected => 'danger',
            self::Sold => 'info',
            self::Inactive => 'gray',
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
