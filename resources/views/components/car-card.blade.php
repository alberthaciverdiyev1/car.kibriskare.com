@props(['car'])

@php
    $defaultCarImg = asset('images/car-placeholder.svg');
    $images = $car->images->sortBy('sort_order');
    $firstImage = $images->first()?->thumb ?? $defaultCarImg;
    $allImagePaths = $images->map(fn($img) => $img->thumb)->values()->toArray();
    if (empty($allImagePaths)) {
        $allImagePaths = [$firstImage];
    }
    $hasMultiple = count($allImagePaths) > 1;

    $title = $car->display_title;
    $date = $car->published_at
        ? ($car->published_at->diffInHours(now()) < 24 ? $car->published_at->diffForHumans() : $car->published_at->format('d.m.Y'))
        : '';
@endphp

<div onclick="window.location.href='{{ url(app()->getLocale() . '/araba/' . $car->slug) }}'"
     data-car-id="{{ $car->id }}"
     class="cursor-pointer bg-white border border-gray-200/80 hover:border-[var(--primary)] rounded-2xl overflow-hidden flex flex-col h-full group relative shadow-2xs hover:shadow-lg">

    <!-- Image & Badges Container -->
    <div class="relative overflow-hidden aspect-[16/10] bg-gray-100"
         data-images='@json($allImagePaths)'
         data-current="0">
        <img src="{{ $allImagePaths[0] }}"
             alt="{{ $title }}"
             loading="lazy"
             decoding="async"
             width="400"
             height="250"
             class="card-image w-full h-full object-cover group-hover:scale-105" />

        <!-- Image Dots -->
        @if($hasMultiple)
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 z-10">
                @foreach($allImagePaths as $i => $img)
                    <span class="block w-1.5 h-1.5 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/50' }}"></span>
                @endforeach
            </div>
            <button onclick="event.stopPropagation(); prevImage(this)" class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white w-7 h-7 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 z-10 cursor-pointer">
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button onclick="event.stopPropagation(); nextImage(this)" class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/70 text-white w-7 h-7 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 z-10 cursor-pointer">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        @endif

        <!-- Premium / Təcili / Endirim / Status Badges -->
        <div class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 flex flex-col gap-1 z-10">
            @if($car->is_premium)
                <span class="bg-[#ffd700] text-gray-950 text-[9px] sm:text-[11px] font-black px-1.5 sm:px-2.5 py-0.5 rounded-md sm:rounded-lg shadow-xs flex items-center gap-1 border border-yellow-400 tracking-wider">
                    <i class="bi bi-gem text-[9px] sm:text-[10px] text-gray-950"></i> PREMIUM
                </span>
            @endif

            @if($car->is_urgent)
                <span class="bg-rose-600 text-white text-[9px] sm:text-[10px] font-black px-1.5 sm:px-2 py-0.5 rounded-md shadow-xs tracking-wider flex items-center gap-0.5">
                    <i class="bi bi-lightning-charge-fill text-[9px]"></i> TƏCİLİ
                </span>
            @endif

            @if($car->hasPriceDrop())
                <span class="bg-emerald-700 text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md shadow-xs flex items-center gap-0.5">
                    <i class="bi bi-arrow-down-short text-xs"></i>
                    @if($car->price_drop_percentage) -%{{ $car->price_drop_percentage }} @else ENDİRİM @endif
                </span>
            @endif

            @if($car->is_plate_masked)
                <span class="bg-gray-800 text-white text-[8px] sm:text-[9px] font-semibold px-1.5 py-0.5 rounded-md shadow-xs flex items-center gap-1 opacity-90">
                    <i class="bi bi-shield-lock-fill text-[8px]"></i> Plaka Gizli
                </span>
            @endif

            @if(in_array($car->deal_type->value, ['rent_daily', 'rent_monthly', 'rent']))
                <span class="bg-emerald-600 text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-md shadow-xs">
                    KİRAYƏ
                </span>
            @endif
        </div>

        <!-- Top Right Actions: Compare & Favorite -->
        <div class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 flex gap-1 sm:gap-1.5 z-10">
            <button type="button"
                    title="{{ __('compare.compare') ?? 'Müqayisə et' }}"
                    data-compare-btn="{{ $car->id }}"
                    onclick="event.stopPropagation(); toggleCompare(this, {{ $car->id }})"
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white/90 hover:bg-white text-gray-700 hover:text-[var(--primary)] backdrop-blur-xs flex items-center justify-center shadow-sm cursor-pointer">
                <i class="bi bi-arrow-left-right text-xs"></i>
            </button>
            <button type="button"
                    title="{{ __('favorites.add_to_favorites') }}"
                    data-fav-btn="{{ $car->id }}"
                    onclick="event.stopPropagation(); toggleFavorite(this, {{ $car->id }})"
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white/90 hover:bg-white text-gray-700 hover:text-rose-500 backdrop-blur-xs flex items-center justify-center shadow-sm cursor-pointer">
                <i class="fa-regular fa-heart text-xs sm:text-sm"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="p-2.5 sm:p-4 flex flex-col flex-1 justify-between gap-2 sm:gap-2.5">
        <div>
            <!-- Price & Deal Type -->
            <div class="flex items-baseline justify-between gap-1.5 mb-1">
                <div class="flex flex-wrap items-baseline gap-1.5 truncate">
                    <div class="text-sm sm:text-lg lg:text-xl font-extrabold text-gray-900 tracking-tight truncate">
                        {{ $car->formatted_price }}
                        @if($car->deal_type->value === 'rent_daily')
                            <span class="text-[10px] sm:text-xs font-normal text-gray-500">/ gün</span>
                        @endif
                    </div>
                    @if($car->hasPriceDrop())
                        <span class="text-[10px] sm:text-xs font-semibold text-gray-400 line-through">
                            {{ $car->formatted_old_price }}
                        </span>
                    @endif
                </div>
                @if($car->autosalon)
                    <span title="{{ $car->autosalon->name }}" class="text-emerald-700 bg-emerald-50 w-5 h-5 sm:w-6 sm:h-6 rounded-md flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-check text-xs sm:text-sm"></i>
                    </span>
                @endif
            </div>

            <!-- Title -->
            <h3 class="font-bold text-gray-900 text-xs sm:text-[15px] leading-snug line-clamp-1 group-hover:text-[var(--primary)]">
                {{ $title }}
            </h3>

            <!-- Specs Grid (Year • Engine • Mileage • Transmission) -->
            <div class="grid grid-cols-2 gap-x-1.5 sm:gap-x-2 gap-y-1 sm:gap-y-1.5 mt-2 sm:mt-2.5 pt-2 sm:pt-2.5 border-t border-gray-100 text-[10px] sm:text-xs text-gray-600 font-medium">
                <div class="flex items-center gap-1 sm:gap-1.5 truncate">
                    <i class="bi bi-calendar3 text-gray-400 text-[10px] sm:text-xs shrink-0"></i>
                    <span class="truncate">{{ $car->year }} il</span>
                </div>
                <div class="flex items-center gap-1 sm:gap-1.5 truncate">
                    <i class="bi bi-speedometer2 text-gray-400 text-[10px] sm:text-xs shrink-0"></i>
                    <span class="truncate">{{ $car->formatted_mileage }}</span>
                </div>
                <div class="flex items-center gap-1 sm:gap-1.5 truncate">
                    <i class="bi bi-fuel-pump text-gray-400 text-[10px] sm:text-xs shrink-0"></i>
                    <span class="truncate">{{ $car->fuel_type->label() }}</span>
                </div>
                <div class="flex items-center gap-1 sm:gap-1.5 truncate">
                    <i class="bi bi-gear text-gray-400 text-[10px] sm:text-xs shrink-0"></i>
                    <span class="truncate">{{ $car->transmission->label() }}</span>
                </div>
            </div>
        </div>

        <!-- Footer: Location & Date -->
        <div class="pt-1.5 sm:pt-2 border-t border-gray-100/80 flex items-center justify-between text-[10px] sm:text-[11px] text-gray-400 gap-1">
            <span class="flex items-center gap-1 text-gray-500 font-medium truncate">
                <i class="bi bi-geo-alt text-gray-400 shrink-0"></i>
                <span class="truncate">{{ $car->city?->getTrans('name') ?? 'Kuzey Kıbrıs' }}</span>
            </span>
            <span class="shrink-0 text-[9px] sm:text-[11px]">{{ $date }}</span>
        </div>
    </div>
</div>
