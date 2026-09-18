<?php

namespace App\Modules\Car\Enums;

enum ImportOrigin: string
{
    case Japan = 'japan';
    case Uk = 'uk';
    case KktcDealer = 'kktc_dealer';
    case Europe = 'europe';
    case Turkey = 'turkey';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Japan => __('Japonya İthal'),
            self::Uk => __('İngiltere (UK) İthal'),
            self::KktcDealer => __('KKTC Bayi Çıkışlı (0 km)'),
            self::Europe => __('Avrupa İthal'),
            self::Turkey => __('Türkiye Çıkışlı'),
            self::Other => __('Diğer'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->toArray();
    }
}
