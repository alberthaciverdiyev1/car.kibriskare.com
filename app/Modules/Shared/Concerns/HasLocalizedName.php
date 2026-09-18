<?php

namespace App\Modules\Shared\Concerns;

trait HasLocalizedName
{
    public function getLocalizedNameAttribute(): string
    {
        return $this->getTrans('name');
    }

    public function getTrans(string $field = 'name', ?string $locale = null, string $default = ''): string
    {
        $locale = $locale ?: app()->getLocale();
        $value = $this->{$field} ?? null;
        if (is_array($value)) {
            return $value[$locale]
                ?? $value['tr']
                ?? $value['az']
                ?? $value['en']
                ?? $value['ru']
                ?? (string)(reset($value) ?: $default);
        }

        return (string)($value ?? $default);
    }
}
