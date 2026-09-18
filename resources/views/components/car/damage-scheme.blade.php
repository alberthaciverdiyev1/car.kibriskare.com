@props([
    'car' => null,
    'damageParts' => [],
    'mode' => 'display', // 'display' or 'edit'
    'hasTramer' => false,
    'tramerAmount' => null,
    'tramerCurrency' => 'GBP',
    'isHeavyDamaged' => false,
    'inspectionPdf' => null,
])

@php
    $partsMap = [
        'hood' => __('Ön Kaput'),
        'roof' => __('Tavan'),
        'trunk' => __('Bagaj Kapağı'),
        'front_bumper' => __('Ön Tampon'),
        'rear_bumper' => __('Arka Tampon'),
        'front_left_fender' => __('Sol Ön Çamurluk'),
        'front_left_door' => __('Sol Ön Kapı'),
        'rear_left_door' => __('Sol Arka Kapı'),
        'rear_left_fender' => __('Sol Arka Çamurluk'),
        'front_right_fender' => __('Sağ Ön Çamurluk'),
        'front_right_door' => __('Sağ Ön Kapı'),
        'rear_right_door' => __('Sağ Arka Kapı'),
        'rear_right_fender' => __('Sağ Arka Çamurluk'),
    ];

    $statuses = [
        'original' => [
            'label' => __('Orijinal'),
            'bg' => 'bg-emerald-500',
            'border' => 'border-emerald-500',
            'text' => 'text-emerald-700',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'color' => '#10B981',
        ],
        'painted' => [
            'label' => __('Boyalı'),
            'bg' => 'bg-amber-500',
            'border' => 'border-amber-500',
            'text' => 'text-amber-700',
            'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
            'color' => '#F59E0B',
        ],
        'local_painted' => [
            'label' => __('Lokal Boyalı'),
            'bg' => 'bg-sky-500',
            'border' => 'border-sky-500',
            'text' => 'text-sky-700',
            'badge' => 'bg-sky-100 text-sky-800 border-sky-300',
            'color' => '#0284C7',
        ],
        'replaced' => [
            'label' => __('Değişen'),
            'bg' => 'bg-rose-500',
            'border' => 'border-rose-500',
            'text' => 'text-rose-700',
            'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
            'color' => '#EF4444',
        ],
    ];

    $currentParts = is_array($damageParts) ? $damageParts : (is_array($car?->damage_parts) ? $car->damage_parts : []);

    // Summary counts
    $counts = [
        'original' => 0,
        'painted' => 0,
        'local_painted' => 0,
        'replaced' => 0,
    ];
    foreach ($partsMap as $key => $name) {
        $st = $currentParts[$key] ?? 'original';
        if (isset($counts[$st])) {
            $counts[$st]++;
        } else {
            $counts['original']++;
        }
    }
@endphp

@if($mode === 'display')
<div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                {{ __('Ekspertiz, Boya & Değişen Durumu') }}
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('Aracın kaporta parçalarının güncel ekspertiz durumu') }}</p>
        </div>

        @if($inspectionPdf || $car?->inspection_pdf)
            <a href="{{ Storage::url($inspectionPdf ?? $car->inspection_pdf) }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors border border-indigo-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ __('Ekspertiz Raporu (PDF)') }}
            </a>
        @endif
    </div>

    <!-- Tramer & Hasar Özet Rozetleri -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="p-3.5 rounded-xl border {{ ($car?->is_heavy_damaged ?? $isHeavyDamaged) ? 'bg-rose-50 border-rose-200' : 'bg-gray-50 border-gray-200' }}">
            <div class="text-xs text-gray-500 font-medium">{{ __('Ağır Hasar / Pert') }}</div>
            <div class="text-sm font-bold mt-1 {{ ($car?->is_heavy_damaged ?? $isHeavyDamaged) ? 'text-rose-600' : 'text-gray-800' }}">
                {{ ($car?->is_heavy_damaged ?? $isHeavyDamaged) ? __('Ağır Hasar Kayıtlı') : __('Hayır (Temiz)') }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl border {{ ($car?->has_tramer ?? $hasTramer) ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-200' }}">
            <div class="text-xs text-gray-500 font-medium">{{ __('Tramer / Hasar Kaydı') }}</div>
            <div class="text-sm font-bold mt-1 text-gray-800">
                @if($car?->has_tramer ?? $hasTramer)
                    @php $amt = $car?->tramer_amount ?? $tramerAmount; @endphp
                    {{ $amt ? number_format($amt, 0, '.', ',') . ' ' . ($car?->tramer_currency ?? $tramerCurrency) : __('Var') }}
                @else
                    {{ __('Kayıt Yok') }}
                @endif
            </div>
        </div>

        <div class="p-3.5 rounded-xl border bg-emerald-50 border-emerald-200">
            <div class="text-xs text-emerald-600 font-medium">{{ __('Orijinal Parça') }}</div>
            <div class="text-sm font-bold mt-1 text-emerald-700">{{ $counts['original'] }} {{ __('Parça') }}</div>
        </div>

        <div class="p-3.5 rounded-xl border {{ ($counts['painted'] + $counts['replaced'] + $counts['local_painted']) > 0 ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-200' }}">
            <div class="text-xs text-gray-500 font-medium">{{ __('Boya / Değişen') }}</div>
            <div class="text-sm font-bold mt-1 text-gray-800">
                {{ $counts['painted'] + $counts['local_painted'] }} {{ __('Boyalı') }} / {{ $counts['replaced'] }} {{ __('Değişen') }}
            </div>
        </div>
    </div>

    <!-- Lejant (Renk Açıklamaları) -->
    <div class="flex flex-wrap items-center gap-3 p-3 bg-gray-50 rounded-xl mb-6 text-xs font-semibold text-gray-700">
        <span class="text-gray-400 font-normal mr-1">{{ __('Renkler:') }}</span>
        @foreach($statuses as $stKey => $stData)
            <div class="inline-flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-full {{ $stData['bg'] }}"></span>
                <span>{{ $stData['label'] }}</span>
            </div>
        @endforeach
    </div>

    <!-- Görsel Şema: İnteraktif Üstten Görünüm Planı -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
        <!-- Sol Kolon: Sol Taraf Parçaları -->
        <div class="md:col-span-4 space-y-2">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-right md:text-left">{{ __('Sol Taraf') }}</h4>
            @foreach(['front_left_fender', 'front_left_door', 'rear_left_door', 'rear_left_fender'] as $pKey)
                @php $st = $currentParts[$pKey] ?? 'original'; $info = $statuses[$st] ?? $statuses['original']; @endphp
                <div class="flex items-center justify-between p-2.5 rounded-lg border {{ $info['border'] }} bg-white hover:bg-gray-50 transition-colors shadow-xs">
                    <span class="text-xs font-medium text-gray-800">{{ $partsMap[$pKey] }}</span>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md {{ $info['badge'] }} border">{{ $info['label'] }}</span>
                </div>
            @endforeach
        </div>

        <!-- Orta Kolon: Ön, Tavan, Arka & Şematik Araba -->
        <div class="md:col-span-4 flex flex-col items-center space-y-2">
            @php $fBumper = $currentParts['front_bumper'] ?? 'original'; $fBInfo = $statuses[$fBumper]; @endphp
            <div class="w-full text-center p-2 rounded-lg border {{ $fBInfo['border'] }} bg-white shadow-xs">
                <div class="text-xs font-medium text-gray-800">{{ $partsMap['front_bumper'] }}</div>
                <div class="text-[11px] font-bold {{ $fBInfo['text'] }}">{{ $fBInfo['label'] }}</div>
            </div>

            @php $hood = $currentParts['hood'] ?? 'original'; $hInfo = $statuses[$hood]; @endphp
            <div class="w-full text-center p-3 rounded-xl border-2 {{ $hInfo['border'] }} bg-white shadow-xs">
                <div class="text-xs font-bold text-gray-900">{{ $partsMap['hood'] }}</div>
                <div class="text-[11px] font-bold {{ $hInfo['text'] }}">{{ $hInfo['label'] }}</div>
            </div>

            @php $roof = $currentParts['roof'] ?? 'original'; $rInfo = $statuses[$roof]; @endphp
            <div class="w-full text-center p-4 rounded-xl border-2 {{ $rInfo['border'] }} bg-white shadow-xs">
                <div class="text-xs font-bold text-gray-900">{{ $partsMap['roof'] }}</div>
                <div class="text-[11px] font-bold {{ $rInfo['text'] }}">{{ $rInfo['label'] }}</div>
            </div>

            @php $trunk = $currentParts['trunk'] ?? 'original'; $tInfo = $statuses[$trunk]; @endphp
            <div class="w-full text-center p-3 rounded-xl border-2 {{ $tInfo['border'] }} bg-white shadow-xs">
                <div class="text-xs font-bold text-gray-900">{{ $partsMap['trunk'] }}</div>
                <div class="text-[11px] font-bold {{ $tInfo['text'] }}">{{ $tInfo['label'] }}</div>
            </div>

            @php $rBumper = $currentParts['rear_bumper'] ?? 'original'; $rBInfo = $statuses[$rBumper]; @endphp
            <div class="w-full text-center p-2 rounded-lg border {{ $rBInfo['border'] }} bg-white shadow-xs">
                <div class="text-xs font-medium text-gray-800">{{ $partsMap['rear_bumper'] }}</div>
                <div class="text-[11px] font-bold {{ $rBInfo['text'] }}">{{ $rBInfo['label'] }}</div>
            </div>
        </div>

        <!-- Sağ Kolon: Sağ Taraf Parçaları -->
        <div class="md:col-span-4 space-y-2">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Sağ Taraf') }}</h4>
            @foreach(['front_right_fender', 'front_right_door', 'rear_right_door', 'rear_right_fender'] as $pKey)
                @php $st = $currentParts[$pKey] ?? 'original'; $info = $statuses[$st] ?? $statuses['original']; @endphp
                <div class="flex items-center justify-between p-2.5 rounded-lg border {{ $info['border'] }} bg-white hover:bg-gray-50 transition-colors shadow-xs">
                    <span class="text-xs font-medium text-gray-800">{{ $partsMap[$pKey] }}</span>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md {{ $info['badge'] }} border">{{ $info['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

@else
<!-- Edit Modu (İlan Ekleme ve Düzenleme Ekranı) -->
<div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
    <div class="mb-4">
        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ __('Kaporta Boya & Değişen Durumu (Ekspertiz Şeması)') }}
        </h4>
        <p class="text-xs text-gray-500 mt-1">{{ __('Aracın kaporta durumunu seçiniz. Varsayılan olarak tüm parçalar "Orijinal"dir.') }}</p>
    </div>

    <!-- Tramer & Pert Bilgisi -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 p-4 bg-white rounded-xl border border-gray-200">
        <div>
            <label class="flex items-center gap-2 text-xs font-semibold text-gray-800 cursor-pointer">
                <input type="checkbox" name="has_tramer" value="1" id="has_tramer_toggle"
                       {{ old('has_tramer', $car?->has_tramer ?? $hasTramer) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                <span>{{ __('Tramer / Hasar Kaydı Var') }}</span>
            </label>
            <div id="tramer_details" class="mt-2 {{ old('has_tramer', $car?->has_tramer ?? $hasTramer) ? '' : 'hidden' }}">
                <div class="flex items-center gap-2">
                    <input type="number" name="tramer_amount" placeholder="{{ __('Tutar') }}"
                           value="{{ old('tramer_amount', $car?->tramer_amount ?? $tramerAmount) }}"
                           class="w-full text-xs px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <select name="tramer_currency" class="text-xs px-2 py-1.5 rounded-lg border border-gray-300">
                        <option value="GBP" {{ old('tramer_currency', $car?->tramer_currency ?? 'GBP') === 'GBP' ? 'selected' : '' }}>£</option>
                        <option value="TRY" {{ old('tramer_currency', $car?->tramer_currency ?? 'GBP') === 'TRY' ? 'selected' : '' }}>₺</option>
                        <option value="EUR" {{ old('tramer_currency', $car?->tramer_currency ?? 'GBP') === 'EUR' ? 'selected' : '' }}>€</option>
                        <option value="USD" {{ old('tramer_currency', $car?->tramer_currency ?? 'GBP') === 'USD' ? 'selected' : '' }}>$</option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <label class="flex items-center gap-2 text-xs font-semibold text-gray-800 cursor-pointer">
                <input type="checkbox" name="is_heavy_damaged" value="1"
                       {{ old('is_heavy_damaged', $car?->is_heavy_damaged ?? $isHeavyDamaged) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-rose-600 focus:ring-rose-500 w-4 h-4">
                <span class="text-rose-600">{{ __('Ağır Hasarlı / Pert Kayıtlı') }}</span>
            </label>
            <p class="text-[11px] text-gray-400 mt-1">{{ __('Araç geçmişinde pert/ağır hasar varsa işaretleyiniz.') }}</p>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-800 mb-1">{{ __('Ekspertiz Raporu (PDF / Görsel)') }}</label>
            <input type="file" name="inspection_pdf" accept=".pdf,image/*"
                   class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @if($car?->inspection_pdf)
                <a href="{{ Storage::url($car->inspection_pdf) }}" target="_blank" class="text-[11px] text-indigo-600 hover:underline mt-1 inline-block">
                    {{ __('Mövcud rapora bax') }}
                </a>
            @endif
        </div>
    </div>

    <!-- Parça Bazlı Seçim Tablosu -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($partsMap as $pKey => $pLabel)
            @php $currentVal = old("damage_parts.{$pKey}", $currentParts[$pKey] ?? 'original'); @endphp
            <div class="p-3 bg-white rounded-xl border border-gray-200">
                <div class="text-xs font-bold text-gray-900 mb-2">{{ $pLabel }}</div>
                <div class="grid grid-cols-2 gap-1 text-[11px]">
                    @foreach($statuses as $stKey => $stData)
                        <label class="flex items-center gap-1.5 p-1 rounded hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="damage_parts[{{ $pKey }}]" value="{{ $stKey }}"
                                   {{ $currentVal === $stKey ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500 h-3.5 w-3.5">
                            <span class="truncate {{ $currentVal === $stKey ? 'font-bold ' . $stData['text'] : 'text-gray-600' }}">{{ $stData['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tramerToggle = document.getElementById('has_tramer_toggle');
        const tramerDetails = document.getElementById('tramer_details');
        if (tramerToggle && tramerDetails) {
            tramerToggle.addEventListener('change', function() {
                if (this.checked) {
                    tramerDetails.classList.remove('hidden');
                } else {
                    tramerDetails.classList.add('hidden');
                }
            });
        }
    });
</script>
@endif
