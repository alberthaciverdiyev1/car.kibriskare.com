<?php

namespace App\Services;

use App\Modules\Car\Models\Car;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    protected string $token = '8208871985:AAHJfMGc5x7J6xsrXkimozyWPwRle_cmoqk';
    protected string $apiUrl = 'https://api.telegram.org/bot';

    protected function getApiUrl(): string
    {
        return $this->apiUrl . $this->token;
    }

    public function setWebhook(string $url): bool
    {
        $response = Http::post($this->getApiUrl() . '/setWebhook', [
            'url' => $url,
        ]);

        return $response->json('ok', false);
    }

    public function sendNewListingNotification($model): void
    {
        $chatIdPath = storage_path('app/telegram_chat_id.txt');
        $chatId = file_exists($chatIdPath) ? trim(file_get_contents($chatIdPath)) : null;
        
        if (!$chatId) {
            Log::warning('Telegram admin chat ID not set. Send /start to the bot.');
            return;
        }

        $type = '';
        $details = '';
        $id = $model->id;
        $title = $this->getTranslatableString($model->title, 'Başlıqsız');
        $user = $model->user;
        
        $contactName = $this->getTranslatableString($model->contact_name ?? $user?->name, 'Qeyd olunmayıb');
        $contactPhone = $this->getTranslatableString($model->contact_phone, 'Qeyd olunmayıb');

        $description = $this->getTranslatableString($model->description, '');
        $description = strip_tags($description);
        $description = \Illuminate\Support\Str::limit($description, 250);
        $descText = $description ? "📝 *Təsvir:* {$description}\n" : '';

        if ($model instanceof \App\Modules\Car\Models\Car) {
            $type = 'car';
            $title = $model->display_title;
            $price = $model->formatted_price;
            $city = $this->getTranslatableString($model->city?->name, '-');
            $year = $model->year;
            $mileage = $model->formatted_mileage;
            $fuel = $model->fuel_type?->label() ?? '-';
            $transmission = $model->transmission?->label() ?? '-';

            $details = "🚗 *YENİ AVTOMOBİL ELANI*\n"
                . "🏷️ *Avtomobil:* {$title}\n"
                . "💰 *Qiymət:* {$price}\n"
                . "📅 *İl:* {$year} | 🛣️ *Yürüş:* {$mileage}\n"
                . "⚙️ *Transmissiya:* {$transmission} | ⛽ *Yanacaq:* {$fuel}\n"
                . "📍 *Şəhər:* {$city}\n"
                . $descText;
        }

        $message = $details
            . "👤 *Əlaqədar şəxs:* {$contactName}\n"
            . "📞 *Telefon:* {$contactPhone}\n"
            . "🆔 *Sistem ID:* #{$id}\n\n"
            . "Zəhmət olmasa bu elanı təsdiq edin və ya imtina edin.";

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '✅ Təsdiqlə', 'callback_data' => "approve_{$type}_{$id}"],
                    ['text' => '❌ İmtina et', 'callback_data' => "reject_prompt_{$type}_{$id}"],
                ]
            ]
        ];

        $images = $model->images;
        $imageCount = $images ? $images->count() : 0;

        $sent = false;

        if ($imageCount > 1) {
            // Send Media Group (up to 10 photos)
            $media = [];
            foreach ($images->take(10) as $img) {
                $media[] = [
                    'type' => 'photo',
                    'media' => $img->url,
                ];
            }

            $responseGroup = Http::post($this->getApiUrl() . '/sendMediaGroup', [
                'chat_id' => $chatId,
                'media' => json_encode($media),
            ]);

            if ($responseGroup->json('ok', false)) {
                // Send the listing message with keyboard right after the album
                $responseMsg = Http::post($this->getApiUrl() . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => json_encode($keyboard),
                ]);

                if ($responseMsg->json('ok', false)) {
                    $sent = true;
                }
            } else {
                Log::warning('Telegram sendMediaGroup failed, falling back: ' . $responseGroup->body());
            }
        } elseif ($imageCount === 1) {
            // Send single photo with inline keyboard caption
            $imageUrl = $images->first()->url;
            $caption = \Illuminate\Support\Str::limit($message, 1000);
            
            $response = Http::post($this->getApiUrl() . '/sendPhoto', [
                'chat_id' => $chatId,
                'photo' => $imageUrl,
                'caption' => $caption,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode($keyboard),
            ]);
            
            if ($response->json('ok', false)) {
                $sent = true;
            } else {
                Log::warning('Telegram sendPhoto failed: ' . $response->body());
            }
        }

        if (!$sent) {
            Http::post($this->getApiUrl() . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode($keyboard),
            ]);
        }
    }

    public function handleWebhook(array $update): void
    {
        // 1. Handle Callback Query (Buttons)
        if (isset($update['callback_query'])) {
            $callbackQuery = $update['callback_query'];
            $callbackId = $callbackQuery['id'];
            $chatId = $callbackQuery['message']['chat']['id'];
            $messageId = $callbackQuery['message']['message_id'];
            $data = $callbackQuery['data'];
            $originalText = $callbackQuery['message']['text'] ?? $callbackQuery['message']['caption'] ?? '';

            if (preg_match('/^approve_(car|request)_(\d+)$/', $data, $matches)) {
                $type = $matches[1];
                $id = $matches[2];

                $model = $this->getModelInstance($type, (int) $id);
                if ($model) {
                    $model->status = $type === 'car' ? \App\Modules\Car\Enums\CarStatus::Active : 'published';
                    if (isset($model->rejection_reason)) {
                        $model->rejection_reason = null;
                    }
                    $model->save();

                    // Clean status suffix and append new one
                    $cleanText = $this->cleanStatusSuffix($originalText);
                    $newText = $cleanText . "\n\n🟢 *TƏSDİQLƏNDİ* (Admin tərəfindən qəbul edildi)";
                    $hasCaption = isset($callbackQuery['message']['caption']);
                    $method = $hasCaption ? '/editMessageCaption' : '/editMessageText';
                    $paramName = $hasCaption ? 'caption' : 'text';

                    // Update keyboard: allow rejection later
                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => '❌ İmtina et', 'callback_data' => "reject_prompt_{$type}_{$id}"],
                            ]
                        ]
                    ];

                    Http::post($this->getApiUrl() . $method, [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        $paramName => $newText,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => json_encode($keyboard),
                    ]);

                    Http::post($this->getApiUrl() . '/answerCallbackQuery', [
                        'callback_query_id' => $callbackId,
                        'text' => 'Elan təsdiqləndi!',
                    ]);
                }
            } elseif (preg_match('/^reject_prompt_(car|request)_(\d+)$/', $data, $matches)) {
                $type = $matches[1];
                $id = $matches[2];

                // Set state in Cache with cleaned text
                $cleanText = $this->cleanStatusSuffix($originalText);
                Cache::put("telegram_state_{$chatId}", [
                    'action' => 'reject',
                    'type' => $type,
                    'id' => $id,
                    'message_id' => $messageId,
                    'original_text' => $cleanText,
                    'has_caption' => isset($callbackQuery['message']['caption'])
                ], now()->addMinutes(10));

                // Send reply request
                Http::post($this->getApiUrl() . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "Lütfən #{$id} ID-li elan üçün imtina səbəbini yazın (Bu mesaja Cavab/Reply verərək yazın):",
                    'reply_markup' => json_encode([
                        'force_reply' => true,
                    ]),
                ]);

                Http::post($this->getApiUrl() . '/answerCallbackQuery', [
                    'callback_query_id' => $callbackId,
                ]);
            }
            return;
        }

        // 2. Handle Text Message
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';

            $lowerText = strtolower(trim($text));
            if ($lowerText === '/start' || str_starts_with($lowerText, '/start@')) {
                @file_put_contents(storage_path('app/telegram_chat_id.txt'), $chatId);
                
                $chatType = $message['chat']['type'] ?? 'private';
                $chatTitle = $message['chat']['title'] ?? 'Şəxsi Çat';
                
                $responseText = "Salam! Bu çat bildirişlərin göndərilməsi üçün yadda saxlanıldı.\n\n"
                    . "ℹ️ *Çat Məlumatı:*\n"
                    . "• *Növ:* {$chatType}\n"
                    . "• *Ad/Başlıq:* {$chatTitle}\n"
                    . "• *ID:* `{$chatId}`\n\n"
                    . "Artıq yeni elanlar və müraciətlər bu çata/qrupa gələcək.";
                
                Http::post($this->getApiUrl() . '/sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $responseText,
                    'parse_mode' => 'Markdown',
                ]);
                return;
            }

            // Check if there is an active rejection state
            $state = Cache::get("telegram_state_{$chatId}");
            if ($state && $state['action'] === 'reject') {
                $type = $state['type'];
                $id = $state['id'];
                $origMsgId = $state['message_id'];
                $originalText = $state['original_text'];

                $model = $this->getModelInstance($type, $id);
                if ($model) {
                    $model->status = $type === 'car' ? \App\Modules\Car\Enums\CarStatus::Rejected : 'rejected';
                    $model->rejection_reason = $text;
                    $model->save();

                    // Update original notification message
                    $newText = $originalText . "\n\n🔴 *İMTİNA EDİLDİ*\n❌ *Səbəb:* {$text}";
                    $hasCaption = $state['has_caption'] ?? false;
                    $method = $hasCaption ? '/editMessageCaption' : '/editMessageText';
                    $paramName = $hasCaption ? 'caption' : 'text';

                    // Update keyboard: allow re-approval (accepting later)
                    $keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => '✅ Yenidən Təsdiqlə', 'callback_data' => "approve_{$type}_{$id}"],
                            ]
                        ]
                    ];

                    Http::post($this->getApiUrl() . $method, [
                        'chat_id' => $chatId,
                        'message_id' => $origMsgId,
                        $paramName => $newText,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => json_encode($keyboard),
                    ]);

                    Http::post($this->getApiUrl() . '/sendMessage', [
                        'chat_id' => $chatId,
                        'text' => "✅ Elan imtina edildi və səbəb qeyd olundu.",
                        'reply_to_message_id' => $message['message_id'],
                    ]);

                    Cache::forget("telegram_state_{$chatId}");
                }
            }
        }
    }

    protected function getModelInstance(string $type, int $id)
    {
        switch ($type) {
            case 'car':
                return \App\Modules\Car\Models\Car::find($id);
        }
        return null;
    }

    protected function getTranslatableString($value, string $default = '-'): string
    {
        if (is_array($value)) {
            return $value['az'] ?? $value['ru'] ?? $value['en'] ?? reset($value) ?? $default;
        }
        return (string) ($value ?? $default);
    }

    protected function cleanStatusSuffix(string $text): string
    {
        $text = preg_replace('/\n\n🟢 \*TƏSDİQLƏNDİ\*.*$/s', '', $text);
        $text = preg_replace('/\n\n🔴 \*İMTİNA EDİLDİ\*.*$/s', '', $text);
        return trim($text);
    }
}
