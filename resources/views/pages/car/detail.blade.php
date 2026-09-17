@extends('layouts.app')

@section('title', $car->display_title . ' - ' . ($car->city?->getTrans('name') ?? 'Kuzey Kıbrıs') . ' | KibrisKare')
@section('meta_description', Str::limit($car->display_description, 160))

@section('content')
    @php
        $galleryImages = $car->images->sortBy('sort_order')->values();
        $defaultImg = asset('images/box-house.jpg');
        $firstImg = $galleryImages->first()?->thumb ?? $defaultImg;
        $totalImages = count($galleryImages);

        $date = $car->published_at
            ? ($car->published_at->diffInHours(now()) < 24 ? $car->published_at->diffForHumans() : $car->published_at->format('d.m.Y'))
            : '';

        $sellerName = $car->autosalon?->name
            ?? ($car->contact_name
            ?? ($car->user?->name
            ?? 'Fərdi Satıcı'));

        $phone = $car->autosalon?->phone ?? $car->contact_phone;
        $whatsapp = $car->autosalon?->whatsapp ?? $car->contact_whatsapp;
    @endphp

    <div class="w-full pt-4">
        @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    </div>

    @include('components.scroll-top')

    <div class="w-full mt-4 sm:mt-6 pb-16">
        
        <!-- Top Title & Price Header (Mobile & Desktop) -->
        <div class="bg-white p-4 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($car->is_vip)
                            <span class="bg-amber-500 text-white text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-sm flex items-center gap-1">
                                <i class="bi bi-star-fill text-[10px]"></i> VIP
                            </span>
                        @endif
                        @if($car->is_premium)
                            <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-sm flex items-center gap-1">
                                <i class="bi bi-gem text-[10px]"></i> PREMIUM
                            </span>
                        @endif
                        <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-lg">
                            {{ $car->deal_type->label() }}
                        </span>
                        <span class="bg-neutral-900 text-white text-xs font-semibold px-2.5 py-0.5 rounded-lg">
                            {{ $car->steering_wheel->value === 'right' ? '🇬🇧 Sağ Sükan' : 'Sol Sükan' }}
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ $car->display_title }}
                    </h1>

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
                            {{ $car->view_count }} baxış
                        </span>
                        <span class="flex items-center gap-1 text-gray-400">
                            İlan №: #CAR-{{ str_pad($car->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <!-- Price Block in Header -->
                <div class="text-left md:text-right shrink-0">
                    <div class="text-2xl sm:text-3xl font-black text-[var(--primary)] tracking-tight">
                        {{ $car->formatted_price }}
                        @if($car->deal_type->value === 'rent_daily')
                            <span class="text-sm font-normal text-gray-500">/ gün</span>
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
                            <span>{{ $totalImages }} Şəkil</span>
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

                <!-- Technical Specifications Table -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="bi bi-cpu text-[var(--primary)] text-xl"></i>
                        <h2 class="text-base sm:text-lg font-bold text-gray-900">Texniki Spesifikasiyalar</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        
                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Marka</span>
                            <span class="font-bold text-gray-900">{{ $car->brand?->name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Model</span>
                            <span class="font-bold text-gray-900">{{ $car->model?->name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Buraxılış İli</span>
                            <span class="font-bold text-gray-900">{{ $car->year }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Yürüş</span>
                            <span class="font-bold text-gray-900">{{ $car->formatted_mileage }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Ban Növü</span>
                            <span class="font-bold text-gray-900">{{ $car->bodyType?->localized_name ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Yanacaq Növü</span>
                            <span class="font-bold text-gray-900">{{ $car->fuel_type->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Sürətlər Qutusu</span>
                            <span class="font-bold text-gray-900">{{ $car->transmission->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Ötürücü</span>
                            <span class="font-bold text-gray-900">{{ $car->drivetrain?->label() ?? '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Mühərrik Həcmi</span>
                            <span class="font-bold text-gray-900">{{ $car->engine_volume_l ?: ($car->engine_volume ? $car->engine_volume . ' cc' : '—') }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Mühərrik Gücü</span>
                            <span class="font-bold text-gray-900">{{ $car->engine_power ? $car->engine_power . ' a.g. (hp)' : '—' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Sükan</span>
                            <span class="font-bold text-gray-900">{{ $car->steering_wheel->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Rəng</span>
                            <span class="font-bold text-gray-900">{{ $car->color ?? '—' }} {{ $car->is_metallic ? '(Metalik)' : '' }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Vəziyyəti</span>
                            <span class="font-bold text-gray-900">{{ $car->condition->label() }}</span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Gömrük / Plaka</span>
                            <span class="font-bold {{ $car->is_customs_cleared ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $car->is_customs_cleared ? 'KKTC Plakalı (Gömrük ödənilib)' : 'Gömrük olunmayıb' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Kredit</span>
                            <span class="font-bold {{ $car->is_credit_available ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->is_credit_available ? 'Mümkündür' : 'Yoxdur' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between py-1.5 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Barter (Takas)</span>
                            <span class="font-bold {{ $car->is_barter_available ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $car->is_barter_available ? 'Mümkündür' : 'Yoxdur' }}
                            </span>
                        </div>

                        @if($car->vin)
                            <div class="flex items-center justify-between py-1.5 border-b border-gray-50 col-span-full">
                                <span class="text-gray-500 font-medium">VIN Kod</span>
                                <span class="font-mono font-bold text-gray-900 tracking-wider bg-gray-100 px-2 py-0.5 rounded">{{ $car->vin }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Features & Equipment -->
                @if($car->features->count() > 0)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="bi bi-sparkles text-[var(--primary)] text-xl"></i>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Təchizat və Komplektasiya</h2>
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

                <!-- Description -->
                @if($car->display_description)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-gray-200/80 shadow-2xs space-y-3">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <i class="bi bi-card-text text-[var(--primary)] text-xl"></i>
                            <h2 class="text-base sm:text-lg font-bold text-gray-900">Elanın Təsviri</h2>
                        </div>

                        <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                            {{ $car->display_description }}
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
                                        <i class="bi bi-patch-check-fill text-xs"></i> Rəsmi Avtosalon
                                    </span>
                                @else
                                    <span>Şəxsi Sahibindən</span>
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
                                $waMsg = urlencode("Salam, KibrisKare.com saytındakı {$car->display_title} elanı ilə bağlı yazıram: " . url()->current());
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}?text={{ $waMsg }}" target="_blank" rel="noopener"
                               class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm transition shadow-sm flex items-center justify-center gap-2">
                                <i class="bi bi-whatsapp text-base"></i>
                                <span>WhatsApp ilə Yaz</span>
                            </a>
                        @endif
                    </div>

                    <!-- Loan / Credit Calculator -->
                    <div class="pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-800">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-calculator text-[var(--primary)]"></i> Avto-Kredit Kalkulyatoru
                            </span>
                            <span class="text-[var(--primary)]" id="calcMonthlyPayment">£ 0 / ay</span>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div>
                                <div class="flex justify-between text-gray-500 mb-1">
                                    <span>İlkin ödəniş (30%):</span>
                                    <span id="downPaymentText">£ {{ number_format($car->price_gbp * 0.3) }}</span>
                                </div>
                                <input type="range" id="downPaymentRange" min="10" max="70" value="30" step="5"
                                       class="w-full accent-[var(--primary)] cursor-pointer">
                            </div>

                            <div>
                                <div class="flex justify-between text-gray-500 mb-1">
                                    <span>Kredit müddəti:</span>
                                    <span id="loanMonthsText">36 ay</span>
                                </div>
                                <input type="range" id="loanMonthsRange" min="12" max="60" value="36" step="6"
                                       class="w-full accent-[var(--primary)] cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Safety Note -->
                    <div class="pt-3 border-t border-gray-100 flex items-start gap-2.5 text-[11px] text-gray-500 leading-tight">
                        <i class="bi bi-shield-lock text-gray-400 text-sm mt-0.5"></i>
                        <span>Avtomobili şəxsən görmədən və texniki yoxlamadan keçirmədən beh və ya ödəniş göndərməyin.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Cars Section -->
        @if($similarCars->count() > 0)
            <div class="mt-12 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Oxşar Avtomobil Elanları</h2>
                    <a href="{{ route('listing', ['brand_id' => $car->brand_id]) }}" class="text-xs font-bold text-[var(--primary)] hover:underline">
                        Bütün {{ $car->brand?->name }} elanları &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($similarCars as $sCar)
                        <x-car-card :car="$sCar" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

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

        // Loan Calculator
        const carPrice = {{ (float)$car->price_gbp }};
        const downRange = document.getElementById('downPaymentRange');
        const monthsRange = document.getElementById('loanMonthsRange');

        function updateLoanCalc() {
            if (!downRange || !monthsRange || carPrice <= 0) return;
            const downPercent = parseInt(downRange.value);
            const months = parseInt(monthsRange.value);

            const downAmount = (carPrice * downPercent) / 100;
            const loanAmount = carPrice - downAmount;
            const annualInterest = 0.09; // 9% annual interest estimate
            const monthlyRate = annualInterest / 12;

            const monthlyPayment = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);

            document.getElementById('downPaymentText').textContent = `£ ${Math.round(downAmount).toLocaleString()} (${downPercent}%)`;
            document.getElementById('loanMonthsText').textContent = `${months} ay`;
            document.getElementById('calcMonthlyPayment').textContent = `£ ${Math.round(monthlyPayment).toLocaleString()} / ay`;
        }

        if (downRange && monthsRange) {
            downRange.addEventListener('input', updateLoanCalc);
            monthsRange.addEventListener('input', updateLoanCalc);
            updateLoanCalc();
        }
    </script>
@endsection
