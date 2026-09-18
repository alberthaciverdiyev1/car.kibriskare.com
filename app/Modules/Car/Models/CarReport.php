<?php

namespace App\Modules\Car\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'user_id',
        'reason',
        'description',
        'contact_info',
        'status',
        'ip_address',
    ];

    public const REASONS = [
        'fake_ad' => 'Sahte / Gerçek Olmayan İlan',
        'wrong_info' => 'Hatalı / Yanıltıcı Bilgi (Fiyat, Km, Hasar)',
        'already_sold' => 'Araç Satılmış / İlan Güncel Değil',
        'scam_deposit' => 'Şüpheli Satıcı / Kapora Dolandırıcılığı Talebi',
        'inappropriate' => 'Uygunsuz İçerik veya Fotoğraf',
        'other' => 'Diğer Nedenler',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }
}
