@extends('layouts.app')

@section('title', $car->display_title . ' - ' . ($car->city?->getTrans('name') ?? 'Kuzey Kıbrıs') . ' | KibrisKare')
@section('meta_description', Str::limit($car->display_description, 160))

@section('content')
    @php
        $galleryImages = $car->images->sortBy('sort_order')->values();
        $defaultImg = asset('images/car-placeholder.svg');
        $firstImg = $galleryImages->first()?->thumb ?? $defaultImg;
        $totalImages = count($galleryImages);

        $date = $car->published_at
            ? ($car->published_at->diffInHours(now()) < 24 ? $car->published_at->diffForHumans() : $car->published_at->format('d.m.Y'))
            : '';

        $sellerName = $car->autosalon?->name
            ?? ($car->contact_name
            ?? ($car->user?->name
            ?? __('car.individual_seller')));

        $phone = $car->autosalon?->phone ?? $car->contact_phone;
        $whatsapp = $car->autosalon?->whatsapp ?? $car->contact_whatsapp;
    @endphp

    <div class="w-full pt-4">
        @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    </div>

    @include('components.scroll-top')

    <div class="w-full mt-4 sm:mt-6 pb-16">
        
        <!-- Owner Action Bar (If logged in seller or admin) -->
        @if(auth()->check() && (auth()->id() === $car->user_id || auth()->user()->isAdmin()))
            <div class="bg-indigo-50 border border-indigo-200/80 rounded-2xl p-4 mb-6 flex flex-wrap items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shrink-0">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-indigo-950">{{ __('Bu elan sizə məxsusdur') }}</div>
                        <div class="text-[11px] text-indigo-600">
                            {{ __('Status') }}: <strong class="uppercase font-extrabold" id="ownerAdStatus">{{ $car->status->value }}</strong>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('cars.edit', $car) }}"
                       class="px-3.5 py-1.5 bg-white hover:bg-gray-50 text-gray-800 border border-gray-300 rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5">
                        <i class="bi bi-pencil-square text-indigo-600"></i>
                        {{ __('Redaktə et') }}
                    </a>

                    <button type="button" onclick="toggleAdSold({{ $car->id }})" id="btnMarkSold"
                            class="px-3.5 py-1.5 {{ $car->status->value === 'sold' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="markSoldText">{{ $car->status->value === 'sold' ? __('Təkrar Yayına Al') : __('Satıldı Olarak İşarələ') }}</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Top Title & Price Header (Mobile & Desktop) -->
        <div class="bg-white p-4 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    @if($car->is_premium || $car->deal_type->value === 'rent_daily')
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            @if($car->is_premium)
                                <span class="bg-[#ffd700] text-gray-950 text-xs font-black px-3 py-1 rounded-lg shadow-xs flex items-center gap-1.5 border border-yellow-400 tracking-wider">
                                    <i class="bi bi-gem text-[11px] text-gray-950"></i> PREMIUM
                                </span>
                            @endif
                            @if($car->deal_type->value === 'rent_daily')
                                <span class="bg-emerald-600 text-white text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-sm">
                                    {{ __('listing.rent') }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $car->display_title }}
                        </h1>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button"
                                    onclick="event.stopPropagation(); toggleCompare(this, {{ $car->id }})"
                                    data-compare-btn="{{ $car->id }}"
                                    class="w-9 h-9 rounded-2xl bg-gray-50 hover:bg-orange-50 hover:text-[var(--primary)] text-gray-600 border border-gray-100 flex items-center justify-center transition cursor-pointer shadow-2xs"
                                    title="{{ __('listing.compare') }}">
                                <i class="bi bi-arrow-left-right text-sm"></i>
                            </button>
                            <button type="button"
                                    onclick="event.stopPropagation(); toggleFavorite(this, {{ $car->id }})"
                                    data-fav-btn="{{ $car->id }}"
                                    class="w-9 h-9 rounded-2xl bg-gray-50 hover:bg-rose-50 hover:text-rose-500 text-gray-600 border border-gray-100 flex items-center justify-center transition cursor-pointer shadow-2xs"
                                    title="{{ __('favorites.add_to_favorites') }}">
                                <i class="fa-regular fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mt-2.5">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-geo-alt text-gray-400"></i>
                            {{ $car->city?->getTrans('name') ?? 'Kuzey Kıbrıs' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-clock text-gray-400"></i>
                            {{ $date }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-eye text-gray-400"></i>
                            {{ $car->view_count }} {{ __('car.view_count') }}
                        </span>
                        <span class="flex items-center gap-1 text-gray-400">
                            {{ __('listing.ad_number') }}: #CAR-{{ str_pad($car->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <!-- Price Block in Header -->
                <div class="text-left md:text-right shrink-0">
                    <div class="text-2xl sm:text-3xl font-black text-[var(--primary)] tracking-tight">
                        {{ $car->formatted_price }}
                        @if($car->deal_type->value === 'rent_daily')
                            <span class="text-sm font-normal text-gray-500">/ {{ __('gün') }}</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400 font-medium mt-0.5 flex items-center md:justify-end gap-2">
                        @if($car->price_try) <span>₺ {{ number_format($car->price_try) }}</span> @endif
                        @if($car->price_eur) <span>€ {{ number_format($car->price_eur) }}</span> @endif
                        @if($car->price_usd) <span>$ {{ number_format($car->price_usd) }}</span> @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2 Columns Grid: 8 cols Left Content + 4 cols Sticky Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Gallery & Details (8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Image Gallery -->
                <div class="bg-white p-3 sm:p-4 rounded-3xl border border-gray-200/80 shadow-2xs space-y-3">
                    <div class="relative w-full h-[320px] sm:h-[420px] md:h-[480px] rounded-2xl overflow-hidden bg-gray-900 group select-none cursor-pointer"
                         onclick="openCarGallery(0)">
                        <img id="carHeroMainImage"
                             src="{{ $firstImg }}"
                             alt="{{ $car->display_title }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.01]" />
                        
                        <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-xs text-white text-xs font-semibold px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                            <i class="bi bi-camera"></i>
                            <span>{{ $totalImages }} {{ __('Fotoğraf') }}</span>
                        </div>
                    </div>

                    <!-- Thumbnails row -->
                    @if($totalImages > 1)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                            @foreach($galleryImages as $idx => $img)
                                <button type="button"
                                        onclick="switchHeroImage('{{ $img->thumb }}', {{ $idx }})"
                                        class="car-thumb-btn w-20 h-16 rounded-xl overflow-hidden shrink-0 border-2 transition {{ $idx === 0 ? 'border-[var(--primary)]' : 'border-transparent opacity-70 hover:opacity-100' }}"
                                        data-idx="{{ $idx }}">
                                    <img src="{{ $img->thumb }}" class="w-full h-full object-cover" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Description (Satıcı Açıklaması) -->
                @if($car->display_description)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-3">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="bi bi-card-text text-[var(--primary)] text-xl"></i>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ __('car.seller_notes') }}</h2>
                        </div>

                        <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                            {{ $car->display_description }}
                        </div>
                    </div>
                @endif

                <!-- Technical Specifications Table -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="bi bi-cpu text-[var(--primary)] text-xl"></i>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ __('car.technical_specs') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.vehicle_category') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->vehicle_type?->label() ?? 'Otomobil' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.brand') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->brand?->name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.model') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->model?->name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.year') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->year }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.mileage') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->formatted_mileage }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.body_type') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->bodyType?->localized_name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.fuel_type') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->fuel_type->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.transmission') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->transmission->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.drivetrain') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->drivetrain?->label() ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.engine_volume') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->engine_volume_l ?: ($car->engine_volume ? $car->engine_volume . ' cc' : '—') }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.engine_power') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->engine_power ? $car->engine_power . ' HP' : '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.steering_wheel') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->steering_wheel->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.color') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->color ?? '—' }} {{ $car->is_metallic ? '(' . __('car.metallic_color') . ')' : '' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.condition') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->condition->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.plate_type') }}</span>
                            <span class="font-bold text-gray-900">
                                {{ $car->plate_type?->label() ?? ($car->is_customs_cleared ? __('KKTC Plakalı (Gümrüğü Ödenmiş)') : __('Yurtdışı Plakalı (Gümrüksüz)')) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.has_warranty') }}</span>
                            <span class="font-bold {{ $car->has_warranty ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->has_warranty ? __('Var') : __('Yok') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('İthalat Menşei') }}</span>
                            <span class="font-bold text-gray-900">{{ $car->import_origin?->label() ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('Seyrüsefer Geçerlilik') }}</span>
                            <span class="font-bold text-gray-900">
                                {{ $car->road_tax_valid_until ? $car->road_tax_valid_until->format('d.m.Y') : '—' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('Muayene Geçerlilik') }}</span>
                            <span class="font-bold text-gray-900">
                                {{ $car->inspection_valid_until ? $car->inspection_valid_until->format('d.m.Y') : '—' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('Koçan / Devir Durumu') }}</span>
                            <span class="font-bold {{ $car->title_deed_ready ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $car->title_deed_ready ? __('Devre Hazır (Sorunsuz)') : __('Devir Bekleniyor') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.is_negotiable') }}</span>
                            <span class="font-bold {{ $car->is_negotiable ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->is_negotiable ? __('Mümkün') : __('Pazarlıksız') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.is_credit_available') }}</span>
                            <span class="font-bold {{ $car->is_credit_available ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->is_credit_available ? __('Var') : __('Yok') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">{{ __('car.is_barter_available') }}</span>
                            <span class="font-bold {{ $car->is_barter_available ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->is_barter_available ? __('Var') : __('Yok') }}
                            </span>
                        </div>

                        @if($car->vin)
                            <div class="flex items-center justify-between py-1.5 border-b border-gray-50 col-span-full">
                                <span class="text-gray-500 font-medium">{{ __('car.vin_code') }}</span>
                                <span class="font-mono font-bold text-gray-900 tracking-wider bg-gray-100 px-2 py-0.5 rounded">{{ $car->vin }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ekspertiz, Boya & Değişen Kaporta Şeması -->
                <x-car.damage-scheme :car="$car" mode="display" />

                <!-- Araç Tanıtım Videosu (Admin tərəfindən aktiv edilibsə) -->
                @if(\App\Modules\Shared\Models\SiteSetting::current()->enable_car_video && $car->embed_video_url)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="bi bi-play-circle-fill text-rose-600 text-xl"></i>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ __('Araç Tanıtım Videosu / Motor Sesi') }}</h2>
                        </div>
                        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-inner">
                            <iframe src="{{ $car->embed_video_url }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif

                <!-- Features & Equipment -->
                @if($car->features->count() > 0)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="bi bi-sparkles text-[var(--primary)] text-xl"></i>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">{{ __('car.features') }}</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($car->features as $feature)
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-gray-50 border border-gray-100 text-xs font-semibold text-gray-800">
                                    <i class="bi bi-check2-circle text-[var(--primary)] text-base"></i>
                                    <span>{{ $feature->localized_name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Sticky Contact & AutoSalon Sidebar (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Seller / AutoSalon Card -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-sm space-y-5 sticky top-24">
                    
                    <div class="flex items-center gap-3.5 pb-4 border-b border-gray-100">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-xl font-bold text-[var(--primary)] shrink-0 overflow-hidden">
                            @if($car->autosalon?->logo)
                                <img src="{{ asset($car->autosalon->logo) }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-person-fill"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base leading-tight">
                                {{ $sellerName }}
                            </h3>
                            <div class="text-xs text-gray-500 font-medium mt-0.5 flex items-center gap-1">
                                @if($car->autosalon)
                                    <span class="text-emerald-700 font-semibold flex items-center gap-0.5">
                                        <i class="bi bi-patch-check-fill text-xs"></i> {{ __('car.dealer_seller') }}
                                    </span>
                                @else
                                    <span>{{ __('car.private_seller') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Actions -->
                    <div class="space-y-2.5">
                        @if($phone)
                            <div class="relative">
                                <button type="button"
                                        id="revealPhoneBtn"
                                        onclick="revealCarPhone('{{ $phone }}')"
                                        class="w-full py-3 px-4 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white font-bold rounded-2xl text-sm transition shadow-sm flex items-center justify-center gap-2">
                                    <i class="bi bi-telephone-fill"></i>
                                    <span id="phoneText">{{ substr($phone, 0, 8) }} •• ••</span>
                                </button>
                            </div>
                        @endif

                        @if($whatsapp)
                            @php
                                $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp);
                                $waMsg = urlencode(__('Merhaba, araba.kibriskare.com sitesindeki :title ilanı ile ilgili bilgi almak istiyorum: :url', ['title' => $car->display_title, 'url' => url()->current()]));
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMsg }}" target="_blank" rel="noopener"
                               class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm transition shadow-sm flex items-center justify-center gap-2">
                                <i class="bi bi-whatsapp text-base"></i>
                                <span>{{ __('car.whatsapp_chat') }}</span>
                            </a>
                        @endif
                    </div>

                    <!-- Promotion Action Buttons (Önə Çək & Premium Et) -->
                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                        <!-- Önə çək -->
                        <div onclick="openAdvanceModal()"
                             class="js-btn-advance bg-white hover:bg-emerald-50/50 border border-gray-200/90 hover:border-emerald-300 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition">{{ __('promotion.advance_ad') }}</span>
                                <span class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold group-hover:bg-emerald-600 group-hover:text-white transition"><i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                            <span class="text-[11px] font-semibold text-blue-600 mt-1.5">{{ __('promotion.from_price', ['amount' => 50]) }}</span>
                        </div>

                        <!-- Premium -->
                        <div onclick="openPremiumModal()"
                             class="js-btn-premium bg-white hover:bg-amber-50/50 border border-gray-200/90 hover:border-amber-300 rounded-2xl p-3 flex flex-col justify-between cursor-pointer transition shadow-2xs group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm font-bold text-gray-800 group-hover:text-amber-700 transition">{{ __('promotion.premium_ad') }}</span>
                                <span class="w-6 h-6 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-xs font-bold group-hover:bg-amber-500 group-hover:text-white transition"><i class="fa-solid fa-crown"></i></span>
                            </div>
                            <span class="text-[11px] font-semibold text-blue-600 mt-1.5">{{ __('promotion.from_price', ['amount' => 150]) }}</span>
                        </div>
                    </div>

                    <!-- Safety Note -->
                    <div class="pt-3 border-t border-gray-100 flex items-start gap-2.5 text-[11px] text-gray-500 leading-tight">
                        <i class="bi bi-shield-lock text-gray-400 text-sm mt-0.5"></i>
                        <span>{{ __('Aracı görmeden ve ekspertiz yaptırmadan kapora veya ön ödeme göndermeyiniz.') }}</span>
                    </div>

                    <!-- Report Ad Button -->
                    <div class="pt-2 border-t border-gray-100">
                        <button type="button" onclick="openReportModal()"
                                class="w-full flex items-center justify-center gap-1.5 py-1.5 text-xs text-gray-400 hover:text-rose-600 transition font-medium">
                            <i class="bi bi-flag text-xs"></i>
                            <span>{{ __('Hatalı / Şüpheli İlanı Bildir') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Cars Section -->
        @if($similarCars->count() > 0)
            <div class="mt-12 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">{{ __('car.similar_cars') }}</h2>
                    <a href="{{ route('listing', ['brand_id' => $car->brand_id]) }}" class="text-xs font-bold text-[var(--primary)] hover:underline">
                        {{ __('Tüm :brand Araçları', ['brand' => $car->brand?->name]) }} &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 lg:gap-5">
                    @foreach($similarCars as $sCar)
                        <x-car-card :car="$sCar" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Report Modal -->
    <x-car.report-modal :car="$car" />

    <!-- Promotion Modals -->
    @include('components.car.move-forward-modal')
    @include('components.car.premium-modal')

    <!-- Gallery Fullscreen Modal -->
    <div id="carGalleryModal" class="fixed inset-0 z-50 hidden bg-black/95 flex flex-col items-center justify-between p-4 select-none">
        <div class="w-full flex items-center justify-between text-white max-w-5xl">
            <span id="galleryCounter" class="text-sm font-semibold text-gray-400">1 / {{ $totalImages }}</span>
            <button type="button" onclick="closeCarGallery()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <div class="relative max-w-5xl max-h-[75vh] flex items-center justify-center my-auto">
            <img id="galleryModalImage" src="{{ $firstImg }}" class="max-w-full max-h-[75vh] object-contain rounded-2xl shadow-2xl" />
            <button type="button" onclick="navGallery(-1)" class="absolute left-2 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center text-xl transition">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button type="button" onclick="navGallery(1)" class="absolute right-2 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center text-xl transition">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="text-center text-white/60 text-xs py-2">
            {{ $car->display_title }}
        </div>
    </div>

    <script>
        // Gallery logic
        const galleryImages = @json($galleryImages->pluck('thumb'));
        let currentImgIdx = 0;

        function switchHeroImage(src, idx) {
            currentImgIdx = idx;
            const hero = document.getElementById('carHeroMainImage');
            if (hero) hero.src = src;
            document.querySelectorAll('.car-thumb-btn').forEach((b, i) => {
                b.classList.toggle('border-[var(--primary)]', i === idx);
                b.classList.toggle('opacity-100', i === idx);
                b.classList.toggle('opacity-70', i !== idx);
                b.classList.toggle('border-transparent', i !== idx);
            });
        }

        function openCarGallery(idx) {
            currentImgIdx = idx;
            const modal = document.getElementById('carGalleryModal');
            if (modal) {
                modal.classList.remove('hidden');
                updateModalView();
            }
        }

        function closeCarGallery() {
            const modal = document.getElementById('carGalleryModal');
            if (modal) modal.classList.add('hidden');
        }

        function navGallery(direction) {
            currentImgIdx += direction;
            if (currentImgIdx < 0) currentImgIdx = galleryImages.length - 1;
            if (currentImgIdx >= galleryImages.length) currentImgIdx = 0;
            updateModalView();
        }

        function updateModalView() {
            const img = document.getElementById('galleryModalImage');
            const counter = document.getElementById('galleryCounter');
            if (img && galleryImages[currentImgIdx]) img.src = galleryImages[currentImgIdx];
            if (counter) counter.textContent = `${currentImgIdx + 1} / ${galleryImages.length}`;
        }

        function revealCarPhone(fullPhone) {
            const phoneText = document.getElementById('phoneText');
            if (phoneText) {
                phoneText.textContent = fullPhone;
                window.location.href = `tel:${fullPhone}`;
            }
        }

        // Promotion modals logic
        function openAdvanceModal() {
            const modal = document.getElementById('modal-advance');
            if (modal) modal.style.display = 'flex';
        }

        function openPremiumModal() {
            const modal = document.getElementById('modal-premium');
            if (modal) modal.style.display = 'flex';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const modalAdvance = document.getElementById('modal-advance');
            const modalPremium = document.getElementById('modal-premium');

            document.querySelectorAll('[data-close]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-close');
                    const targetModal = document.getElementById(targetId);
                    if (targetModal) targetModal.style.display = 'none';
                });
            });

            window.addEventListener('click', function (e) {
                if (e.target === modalAdvance) modalAdvance.style.display = 'none';
                if (e.target === modalPremium) modalPremium.style.display = 'none';
            });
        });

        async function toggleAdSold(carId) {
            const btn = document.getElementById('btnMarkSold');
            const txt = document.getElementById('markSoldText');
            const st = document.getElementById('ownerAdStatus');
            if (!confirm('{{ __('İlan durumunu dəyişmək istədiyinizdən əminsiniz?') }}')) return;

            try {
                const response = await fetch(`/araba/${carId}/satildi-isaretle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                if (data.success) {
                    if (st) st.textContent = data.status;
                    if (data.status === 'sold') {
                        btn.className = 'px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5';
                        if (txt) txt.textContent = '{{ __('Təkrar Yayına Al') }}';
                    } else {
                        btn.className = 'px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-1.5';
                        if (txt) txt.textContent = '{{ __('Satıldı Olarak İşarələ') }}';
                    }
                    alert(data.message);
                } else {
                    alert(data.message || 'Xəta baş verdi');
                }
            } catch (e) {
                alert('Xəta baş verdi.');
            }
        }
    </script>
@endsection
