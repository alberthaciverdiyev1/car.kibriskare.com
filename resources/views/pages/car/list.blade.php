@extends('layouts.app')

@section('title', 'Kuzey Kıbrıs Araba İlanları - Satılık & Rent a Car | KibrisKare')
@section('meta_description', 'Kıbrıs genelinde sahibinden ve galeriden satılık, kiralık ve rent a car araç ilanları. En geniş araba pazarı.')

@section('content')
    <div class="w-full pt-4 pb-16">
        @include('components.scroll-top')

        <section class="py-2 sm:py-4">
            <div class="max-w-[1400px] mx-auto px-2 sm:px-4">
                
                <!-- Main Filter Form -->
                <form id="carFilterForm" method="GET" action="{{ route('listing') }}" class="space-y-3">
                    
                    @php
                        $selectedVehicleType = request('vehicle_type', 'all');
                        $selectedAdType = request('adType', request('deal_type', 'all'));
                    @endphp

                    <!-- Top Bar: Deal Type Tabs + Reset -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <!-- Deal Type (Hamısı / Satılıq / Kirayə) -->
                        <div class="flex gap-1 bg-gray-100 p-1 rounded-2xl border border-gray-200/60 shadow-2xs" data-role="add-type-toggle">
                            <button type="button" data-value="all"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'all' || !$selectedAdType ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                Hamısı
                            </button>
                            <button type="button" data-value="sale"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'sale' ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                Satılıq
                            </button>
                            <button type="button" data-value="rent_daily"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ in_array($selectedAdType, ['rent_daily', 'rent', 'rent_monthly']) ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                Kirayə (Rent a Car)
                            </button>
                            <input type="hidden" name="adType" id="adTypeInput" value="{{ $selectedAdType }}">
                        </div>

                        <!-- Reset Button Top Right -->
                        <div class="flex items-center gap-2">
                            <button type="button" id="resetFiltersBtn" title="Filtrləri Sıfırla"
                                    class="px-3.5 py-2 bg-white border border-gray-200/90 rounded-xl hover:bg-gray-50 text-gray-600 flex items-center justify-center transition shadow-2xs cursor-pointer">
                                <i class="bi bi-arrow-clockwise text-base mr-1.5 text-[var(--primary)]"></i>
                                <span class="text-xs font-bold">{{ __('Sıfırla') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- 2-ROW PRIMARY FILTER CONTAINER -->
                    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm space-y-3.5">
                        
                        <!-- ROW 1: Marka | Model | Condition Segment (Hamısı / Yeni / Sürülmüş) | Şəhər -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            
                            <!-- 1. Marka -->
                            <div class="relative">
                                <select name="brand_id" id="brandSelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 transition outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">Marka</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }} data-applicable-types="{{ json_encode($b->applicable_types ?? []) }}">
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            <!-- 2. Model -->
                            <div class="relative">
                                <select name="model_id" id="modelSelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 transition outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9 {{ count($models) == 0 ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <option value="">Model</option>
                                    @foreach($models as $m)
                                        <option value="{{ $m->id }}" {{ request('model_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            <!-- 3. Condition Segment: [ Yeni | Sürülmüş ] -->
                            @php
                                $currentCondition = request('condition');
                            @endphp
                            <div class="flex h-11 border border-gray-200/90 rounded-xl overflow-hidden bg-white p-0.5 select-none" id="conditionSegmentGroup">
                                <button type="button" data-condition="new"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg transition-colors {{ $currentCondition === 'new' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                    Yeni
                                </button>
                                <button type="button" data-condition="used"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg border-l border-gray-100 transition-colors {{ $currentCondition === 'used' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                    Sürülmüş
                                </button>
                                <input type="hidden" name="condition" id="conditionInput" value="{{ $currentCondition }}">
                            </div>

                            <!-- 4. Şəhər -->
                            <div class="relative">
                                <select name="city_id" id="citySelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 transition outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">Şəhər</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ request('city_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->getTrans('name') }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- ROW 2: [Qiymət min | maks] | Ban növü | [İl min | maks] | Actions -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                            
                            <!-- 1. Qiymət min | maks (Dual Input Box) -->
                            <div class="flex items-center h-11 border border-gray-200/90 rounded-xl bg-white overflow-hidden w-full focus-within:border-[var(--primary)] focus-within:ring-1 focus-within:ring-[var(--primary)]">
                                <input type="number" name="price_min" id="priceMinInput" value="{{ request('price_min') }}" placeholder="Qiymət, min."
                                       class="w-1/2 px-3 h-full bg-transparent text-sm text-gray-800 outline-none placeholder-gray-400">
                                <div class="h-6 w-px bg-gray-200 shrink-0"></div>
                                <input type="number" name="price_max" id="priceMaxInput" value="{{ request('price_max') }}" placeholder="maks."
                                       class="w-1/2 px-3 h-full bg-transparent text-sm text-gray-800 outline-none placeholder-gray-400">
                            </div>

                            <!-- 2. Ban növü -->
                            <div class="relative">
                                <select name="body_type_id" id="bodyTypeSelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 transition outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">Ban növü</option>
                                    @foreach($bodyTypes as $bt)
                                        <option value="{{ $bt->id }}" {{ request('body_type_id') == $bt->id ? 'selected' : '' }} data-applicable-types="{{ json_encode($bt->applicable_types ?? []) }}">
                                            {{ $bt->localized_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            <!-- 3. İl min | maks (Dual Select Box) -->
                            <div class="flex items-center h-11 border border-gray-200/90 rounded-xl bg-white overflow-hidden w-full focus-within:border-[var(--primary)] focus-within:ring-1 focus-within:ring-[var(--primary)]">
                                <div class="relative w-1/2 h-full">
                                    <select name="year_min" id="yearMinSelect"
                                            class="w-full h-full pl-3 pr-6 bg-transparent text-sm text-gray-800 outline-none cursor-pointer appearance-none">
                                        <option value="">İl, min.</option>
                                        @for($y = (int)date('Y') + 1; $y >= 1980; $y--)
                                            <option value="{{ $y }}" {{ request('year_min') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                                </div>
                                <div class="h-6 w-px bg-gray-200 shrink-0"></div>
                                <div class="relative w-1/2 h-full">
                                    <select name="year_max" id="yearMaxSelect"
                                            class="w-full h-full pl-3 pr-6 bg-transparent text-sm text-gray-800 outline-none cursor-pointer appearance-none">
                                        <option value="">maks.</option>
                                        @for($y = (int)date('Y') + 1; $y >= 1980; $y--)
                                            <option value="{{ $y }}" {{ request('year_max') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- 4. Actions: Ətraflı & Elanları göstər -->
                            <div class="flex items-center gap-2 w-full">
                                <button type="button" id="openFilterMoreBtn" title="Ətraflı axtarış"
                                        class="h-11 flex-1 px-4 bg-white hover:bg-gray-50 border border-gray-200/90 text-gray-700 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                                    <i class="bi bi-sliders text-sm text-[var(--primary)]"></i>
                                    <span>Ətraflı</span>
                                </button>
                                <button type="submit"
                                        class="h-11 px-5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-xl text-xs sm:text-sm font-bold transition shadow-sm flex items-center justify-center gap-1.5 shrink-0 cursor-pointer">
                                    <i class="bi bi-search text-xs"></i>
                                    <span>Göstər</span>
                                </button>
                            </div>

                        </div>

                    </div>

                    <!-- Category Navigation: Vehicle Type Cards (Strictly Single Row - 6 Columns) -->
                    <div class="grid grid-cols-6 gap-1.5 sm:gap-2.5 md:gap-3 select-none pt-1" id="vehicleTypeCardsContainer" style="display: grid !important; grid-template-columns: repeat(6, minmax(0, 1fr)) !important;">
                        
                        <!-- 1. Otomobil -->
                        <button type="button" data-type="car"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'car' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'car' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-car-front-fill"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('Otomobil') }}</span>
                        </button>

                        <!-- 2. SUV & Pick-up -->
                        <button type="button" data-type="suv"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'suv' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'suv' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-truck-front-fill"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('SUV & Pick-up') }}</span>
                        </button>

                        <!-- 3. Motosiklet -->
                        <button type="button" data-type="motorcycle"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'motorcycle' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'motorcycle' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-bicycle"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('Motosiklet') }}</span>
                        </button>

                        <!-- 4. Ticari -->
                        <button type="button" data-type="commercial"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'commercial' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'commercial' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-truck"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('Ticari') }}</span>
                        </button>

                        <!-- 5. Klasik -->
                        <button type="button" data-type="classic"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'classic' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'classic' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-gem"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('Klasik') }}</span>
                        </button>

                        <!-- 6. Qəzalı -->
                        <button type="button" data-type="damaged"
                                class="vehicle-type-btn aspect-square p-1.5 sm:p-3 rounded-xl sm:rounded-2xl border text-center flex flex-col items-center justify-center gap-1 sm:gap-2 cursor-pointer group transition duration-200 {{ $selectedVehicleType === 'damaged' ? 'border-[var(--primary)] bg-orange-50/60 text-[var(--primary)] shadow-sm font-extrabold ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-[var(--primary)] hover:bg-orange-50/30 text-gray-700 shadow-2xs font-bold' }}">
                            <div class="icon-box w-7 h-7 sm:w-10 sm:h-10 md:w-11 md:h-11 rounded-lg sm:rounded-xl flex items-center justify-center text-xs sm:text-lg md:text-xl transition {{ $selectedVehicleType === 'damaged' ? 'bg-[var(--primary)] text-white shadow-xs' : 'bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-[var(--primary)]' }}">
                                <i class="bi bi-tools"></i>
                            </div>
                            <span class="text-[10px] sm:text-xs md:text-sm font-bold text-center leading-tight truncate w-full px-0.5">{{ __('Qəzalı') }}</span>
                        </button>

                        <input type="hidden" name="vehicle_type" id="vehicleTypeInput" value="{{ $selectedVehicleType }}">
                    </div>
                </form>

                <!-- Results Header Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 mt-6 mb-4">
                    <div class="flex items-baseline gap-2">
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">{{ __('Elanlar') }}</h2>
                        <span id="carsCountBadge" class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                            {{ $cars->total() }} {{ __('elan') }}
                        </span>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="carSortSelect" class="text-xs font-semibold text-gray-500 hidden sm:inline-block">{{ __('Sıralama:') }}</label>
                        <div class="relative">
                            <select name="sort" id="carSortSelect" form="carFilterForm"
                                    class="h-9 pl-3 pr-8 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 outline-none focus:border-[var(--primary)] cursor-pointer appearance-none shadow-2xs">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('Ən yenilər') }}</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('Qiymət: Ucuzdan bahaya') }}</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('Qiymət: Bahadan ucuza') }}</option>
                                <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>{{ __('İl: Yenidən köhnəyə') }}</option>
                                <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>{{ __('İl: Köhnədən yeniyə') }}</option>
                                <option value="mileage_asc" {{ request('sort') == 'mileage_asc' ? 'selected' : '' }}>{{ __('Yürüş: Azdan çoxa') }}</option>
                                <option value="mileage_desc" {{ request('sort') == 'mileage_desc' ? 'selected' : '' }}>{{ __('Yürüş: Çoxdan aza') }}</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Cars Grid Section -->
                <div id="carsListingWrapper" class="min-h-[400px]">
                    @include('pages.car.partials.cards')
                </div>

                <!-- Pagination Section -->
                <div id="carsPaginationWrapper">
                    @include('pages.car.partials.pagination')
                </div>
            </div>
        </section>

        <!-- Detailed Modal Filter -->
        @include('pages.car.partials.filter-more')
    </div>

    <!-- Client-side script for dynamic filtering & models loading -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('carFilterForm');
            const brandSelect = document.getElementById('brandSelect');
            const modelSelect = document.getElementById('modelSelect');
            const bodyTypeSelect = document.getElementById('bodyTypeSelect');
            const vehicleTypeBtns = document.querySelectorAll('.vehicle-type-btn');
            const vehicleTypeInput = document.getElementById('vehicleTypeInput');
            const dealTypeBtns = document.querySelectorAll('.deal-type-btn');
            const adTypeInput = document.getElementById('adTypeInput');
            const resetBtn = document.getElementById('resetFiltersBtn');
            const conditionBtns = document.querySelectorAll('.condition-btn');
            const conditionInput = document.getElementById('conditionInput');
            const sortSelect = document.getElementById('carSortSelect');

            // Cache options
            const allBrandOptions = brandSelect ? Array.from(brandSelect.querySelectorAll('option')) : [];
            const allBodyTypeOptions = bodyTypeSelect ? Array.from(bodyTypeSelect.querySelectorAll('option')) : [];

            function filterCategoryOptions(selectedType) {
                const isAll = !selectedType || selectedType === 'all';
                const isMotorcycle = selectedType === 'motorcycle';

                // 1. Filter Brand Select
                if (brandSelect) {
                    const currentBrandVal = brandSelect.value;
                    brandSelect.innerHTML = '';
                    let isCurrentBrandValid = false;

                    allBrandOptions.forEach(opt => {
                        if (!opt.value) {
                            brandSelect.appendChild(opt.cloneNode(true));
                            return;
                        }
                        let applicable = [];
                        try {
                            applicable = JSON.parse(opt.getAttribute('data-applicable-types') || '[]');
                        } catch (e) {
                            applicable = [];
                        }

                        if (isAll || !applicable || applicable.length === 0 || applicable.includes('all') || applicable.includes(selectedType)) {
                            const cloned = opt.cloneNode(true);
                            if (opt.value === currentBrandVal) {
                                cloned.selected = true;
                                isCurrentBrandValid = true;
                            }
                            brandSelect.appendChild(cloned);
                        }
                    });

                    if (!isCurrentBrandValid && currentBrandVal) {
                        brandSelect.value = '';
                        if (modelSelect) {
                            modelSelect.innerHTML = '<option value="">Model</option>';
                            modelSelect.disabled = true;
                            modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        }
                    }
                }

                // 2. Filter Body Type Select
                if (bodyTypeSelect) {
                    const currentBodyVal = bodyTypeSelect.value;
                    bodyTypeSelect.innerHTML = '';
                    let isCurrentBodyValid = false;

                    allBodyTypeOptions.forEach(opt => {
                        if (!opt.value) {
                            const placeholder = opt.cloneNode(true);
                            if (isMotorcycle) {
                                placeholder.textContent = 'Motosiklet növü';
                            } else {
                                placeholder.textContent = 'Ban növü';
                            }
                            bodyTypeSelect.appendChild(placeholder);
                            return;
                        }
                        let applicable = [];
                        try {
                            applicable = JSON.parse(opt.getAttribute('data-applicable-types') || '[]');
                        } catch (e) {
                            applicable = [];
                        }

                        if (isAll || !applicable || applicable.length === 0 || applicable.includes('all') || applicable.includes(selectedType)) {
                            const cloned = opt.cloneNode(true);
                            if (opt.value === currentBodyVal) {
                                cloned.selected = true;
                                isCurrentBodyValid = true;
                            }
                            bodyTypeSelect.appendChild(cloned);
                        }
                    });

                    if (!isCurrentBodyValid && currentBodyVal) {
                        bodyTypeSelect.value = '';
                    }
                }

                // 3. Filter Modal Chips
                const modalBodyTypeChips = document.querySelectorAll('.modal-body-type-chip');
                modalBodyTypeChips.forEach(chip => {
                    let applicable = [];
                    try {
                        applicable = JSON.parse(chip.getAttribute('data-applicable-types') || '[]');
                    } catch (e) {
                        applicable = [];
                    }

                    if (isAll || !applicable || applicable.length === 0 || applicable.includes('all') || applicable.includes(selectedType)) {
                        chip.style.display = '';
                    } else {
                        chip.style.display = 'none';
                        const input = chip.querySelector('input[type="radio"]');
                        if (input && input.checked) {
                            input.checked = false;
                            chip.classList.remove('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                            chip.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                        }
                    }
                });

                const modalBodyTypeLabel = document.getElementById('modalBodyTypeLabelText');
                if (modalBodyTypeLabel) {
                    modalBodyTypeLabel.textContent = isMotorcycle ? 'Motosiklet Növü' : 'Ban Növü (Kasa Tipi)';
                }

                const modalSteering = document.getElementById('modalSteeringWheelContainer');
                if (modalSteering) {
                    if (isMotorcycle) {
                        modalSteering.style.display = 'none';
                        const radios = modalSteering.querySelectorAll('input[type="radio"]');
                        radios.forEach(r => r.checked = false);
                    } else {
                        modalSteering.style.display = '';
                    }
                }
            }

            // Run initial filter on page load
            const initialVehicleType = vehicleTypeInput ? vehicleTypeInput.value : 'all';
            filterCategoryOptions(initialVehicleType);

            // 0. Vehicle Type Category Cards Click (Supports click to select / click to unselect)
            vehicleTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const val = this.dataset.type;
                    const isAlreadyActive = vehicleTypeInput && vehicleTypeInput.value === val;
                    const targetVal = isAlreadyActive ? 'all' : val;

                    if (vehicleTypeInput) vehicleTypeInput.value = targetVal;

                    vehicleTypeBtns.forEach(b => {
                        b.classList.remove('border-[var(--primary)]', 'bg-orange-50/50', 'text-[var(--primary)]', 'font-extrabold');
                        b.classList.add('border-gray-200/90', 'bg-white', 'text-gray-700', 'font-bold');
                        const iconBox = b.querySelector('.icon-box');
                        if (iconBox) {
                            iconBox.classList.remove('bg-[var(--primary)]', 'text-white');
                            iconBox.classList.add('bg-gray-100', 'text-gray-600');
                        }
                    });

                    if (!isAlreadyActive) {
                        this.classList.add('border-[var(--primary)]', 'bg-orange-50/50', 'text-[var(--primary)]', 'font-extrabold');
                        this.classList.remove('border-gray-200/90', 'bg-white', 'text-gray-700', 'font-bold');
                        const activeIcon = this.querySelector('.icon-box');
                        if (activeIcon) {
                            activeIcon.classList.add('bg-[var(--primary)]', 'text-white');
                            activeIcon.classList.remove('bg-gray-100', 'text-gray-600');
                        }
                    }

                    filterCategoryOptions(targetVal);
                    submitCarFilter();
                });
            });

            // 0.1 Sort Select Change
            if (sortSelect) {
                sortSelect.addEventListener('change', submitCarFilter);
            }

            // 1. Dynamic Brand -> Models AJAX loading
            if (brandSelect && modelSelect) {
                brandSelect.addEventListener('change', async function() {
                    const brandId = this.value;
                    modelSelect.innerHTML = '<option value="">Yüklənir...</option>';
                    modelSelect.disabled = true;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">Model</option>';
                        modelSelect.disabled = true;
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        submitCarFilter();
                        return;
                    }

                    try {
                        const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                        const data = await res.json();
                        
                        if (data.success && data.models) {
                            modelSelect.innerHTML = '<option value="">Model</option>';
                            data.models.forEach(m => {
                                const opt = document.createElement('option');
                                opt.value = m.id;
                                opt.textContent = m.name;
                                modelSelect.appendChild(opt);
                            });
                            modelSelect.disabled = false;
                            modelSelect.classList.remove('opacity-60', 'cursor-not-allowed');
                        }
                    } catch (err) {
                        console.error('Error fetching models:', err);
                        modelSelect.innerHTML = '<option value="">Model</option>';
                        modelSelect.disabled = false;
                    }

                    submitCarFilter();
                });

                modelSelect.addEventListener('change', submitCarFilter);
            }

            // 2. Condition Segment (Yeni / Sürülmüş toggle)
            conditionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const cond = this.dataset.condition;
                    const isAlreadyActive = this.classList.contains('bg-[#ca1016]');

                    conditionBtns.forEach(b => {
                        b.classList.remove('bg-[#ca1016]', 'text-white', 'shadow-xs');
                        b.classList.add('text-gray-600');
                    });

                    if (isAlreadyActive) {
                        conditionInput.value = '';
                    } else {
                        conditionInput.value = cond;
                        this.classList.add('bg-[#ca1016]', 'text-white', 'shadow-xs');
                        this.classList.remove('text-gray-600');
                    }
                    submitCarFilter();
                });
            });

            // 3. Deal Type Toggle
            dealTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const val = this.dataset.value;
                    adTypeInput.value = val;
                    dealTypeBtns.forEach(b => {
                        b.classList.remove('bg-white', 'text-[var(--primary)]', 'shadow-sm');
                        b.classList.add('text-gray-600');
                    });
                    this.classList.add('bg-white', 'text-[var(--primary)]', 'shadow-sm');
                    this.classList.remove('text-gray-600');
                    submitCarFilter();
                });
            });

            // 4. Reset Button
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    window.location.href = `/${document.documentElement.lang || 'tr'}/ilanlar`;
                });
            }

            // 5. Modal Filter Controls & Interactive Chips
            const modal = document.getElementById('filterMoreModal');
            const modalCard = document.getElementById('filterMoreModalCard');
            const openModalBtn = document.getElementById('openFilterMoreBtn');
            const closeModalBtn = document.getElementById('closeFilterMoreBtn');
            const applyModalBtn = document.getElementById('applyModalFiltersBtn');
            const clearModalBtn = document.getElementById('clearModalFiltersBtn');

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalCard.classList.remove('scale-95');
                }, 10);
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('opacity-0');
                modalCard.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            if (openModalBtn) openModalBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });

                // Radio chip interactive toggle logic
                modal.querySelectorAll('.modal-radio-chip').forEach(chip => {
                    chip.addEventListener('click', function(e) {
                        e.preventDefault();
                        const input = this.querySelector('input[type="radio"]');
                        if (!input) return;

                        const name = input.name;
                        const isCurrentlyChecked = input.checked;

                        // Uncheck all siblings in this radio group
                        modal.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach(r => {
                            r.checked = false;
                            const parent = r.closest('.modal-radio-chip');
                            if (parent) {
                                parent.classList.remove('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                                parent.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                                const icon = parent.querySelector('i');
                                if (icon) {
                                    icon.classList.remove('text-[var(--primary)]');
                                    icon.classList.add('text-gray-400');
                                }
                            }
                        });

                        // Toggle on if not already checked
                        if (!isCurrentlyChecked) {
                            input.checked = true;
                            this.classList.add('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                            this.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                            const icon = this.querySelector('i');
                            if (icon) {
                                icon.classList.add('text-[var(--primary)]');
                                icon.classList.remove('text-gray-400');
                            }
                        }
                    });
                });

                // Checkbox chip interactive toggle logic
                modal.querySelectorAll('.modal-check-chip').forEach(chip => {
                    chip.addEventListener('click', function(e) {
                        const input = this.querySelector('input[type="checkbox"]');
                        if (!input) return;
                        setTimeout(() => {
                            if (input.checked) {
                                this.classList.add('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                                this.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-800');
                            } else {
                                this.classList.remove('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                                this.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-800');
                            }
                        }, 10);
                    });
                });
            }

            if (applyModalBtn) {
                applyModalBtn.addEventListener('click', function() {
                    closeModal();
                    submitCarFilter();
                });
            }

            if (clearModalBtn) {
                clearModalBtn.addEventListener('click', function() {
                    if (modal) {
                        modal.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                        modal.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
                        modal.querySelectorAll('input[type="number"]').forEach(n => n.value = '');
                        modal.querySelectorAll('.modal-radio-chip').forEach(chip => {
                            chip.classList.remove('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                            chip.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                            const icon = chip.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-[var(--primary)]');
                                icon.classList.add('text-gray-400');
                            }
                        });
                        modal.querySelectorAll('.modal-check-chip').forEach(chip => {
                            chip.classList.remove('border-[var(--primary)]', 'bg-orange-50/60', 'font-semibold', 'text-[var(--primary)]');
                            chip.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-800');
                        });
                    }
                    closeModal();
                    submitCarFilter();
                });
            }

            // 6. AJAX form submission and live updates
            async function submitCarFilter() {
                const formData = new FormData(form);
                
                // Append modal form fields into form data
                if (modal) {
                    modal.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked').forEach(input => {
                        if (input.value) {
                            formData.set(input.name, input.value);
                        }
                    });
                    modal.querySelectorAll('input[type="number"]').forEach(input => {
                        if (input.value) {
                            formData.set(input.name, input.value);
                        } else {
                            formData.delete(input.name);
                        }
                    });
                }

                // Clean empty keys
                for (const [key, value] of Array.from(formData.entries())) {
                    if (!value || value === 'all') {
                        formData.delete(key);
                    }
                }

                const params = new URLSearchParams(formData);
                const url = `${form.action}?${params.toString()}`;

                // Update browser URL without reload
                window.history.pushState({}, '', url);

                const wrapper = document.getElementById('carsListingWrapper');
                const pagWrapper = document.getElementById('carsPaginationWrapper');
                const countBadge = document.getElementById('carsCountBadge');

                if (wrapper) {
                    wrapper.classList.add('opacity-50', 'pointer-events-none');
                }

                try {
                    const res = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        cache: 'no-store'
                    });
                    const data = await res.json();

                    if (data.cars && wrapper) {
                        wrapper.innerHTML = data.cars;
                    }
                    if (data.pagination && pagWrapper) {
                        pagWrapper.innerHTML = data.pagination;
                    }
                    if (countBadge && data.total !== undefined) {
                        countBadge.textContent = `${data.total} elan`;
                    }
                } catch (err) {
                    console.error('AJAX filter error:', err);
                } finally {
                    if (wrapper) {
                        wrapper.classList.remove('opacity-50', 'pointer-events-none');
                    }
                }
            }

            // Reload page properly on browser back/forward buttons
            window.addEventListener('popstate', function() {
                window.location.reload();
            });

            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
            });

            // Bind change on inputs
            form.querySelectorAll('select, input[type="number"]').forEach(el => {
                if (el.id !== 'brandSelect' && el.id !== 'modelSelect') {
                    el.addEventListener('change', submitCarFilter);
                }
            });
        });
    </script>
@endsection
