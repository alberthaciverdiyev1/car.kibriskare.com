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
                                {{ __('listing.all') }}
                            </button>
                            <button type="button" data-value="sale"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'sale' ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ __('listing.buy') }}
                            </button>
                            <button type="button" data-value="rent"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ in_array($selectedAdType, ['rent', 'rent_daily', 'rent_monthly']) ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                {{ __('listing.rent') }} (Rent a Car)
                            </button>
                            <input type="hidden" name="adType" id="adTypeInput" value="{{ $selectedAdType }}">
                        </div>

                        <!-- Reset Button Top Right -->
                        <div class="flex items-center gap-2">
                            <button type="button" id="resetFiltersBtn" title="{{ __('listing.reset') }}"
                                    class="px-3.5 py-2 bg-white border border-gray-200/90 rounded-xl hover:bg-gray-50 text-gray-600 flex items-center justify-center transition shadow-2xs cursor-pointer">
                                <i class="bi bi-arrow-clockwise text-base mr-1.5 text-[var(--primary)]"></i>
                                <span class="text-xs font-bold">{{ __('listing.reset') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Category Navigation: Minimalist & Modern Vehicle Type Tabs (Horizontally scrollable on mobile) -->
                    <div class="flex overflow-x-auto no-scrollbar sm:grid sm:grid-cols-4 lg:grid-cols-7 gap-2 sm:gap-2.5 select-none pb-1 -mx-4 px-4 sm:mx-0 sm:px-0" id="vehicleTypeCardsContainer">
                        
                        <!-- 1. Bütün Elanlar -->
                        <button type="button" data-type="all"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'all' || !$selectedVehicleType ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-grid-fill text-base {{ $selectedVehicleType === 'all' || !$selectedVehicleType ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('listing.all') }}</span>
                        </button>

                        <!-- 2. Otomobil -->
                        <button type="button" data-type="car"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'car' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-car-front-fill text-base {{ $selectedVehicleType === 'car' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Otomobil') }}</span>
                        </button>

                        <!-- 3. SUV & Pick-up -->
                        <button type="button" data-type="suv"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'suv' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-truck-front-fill text-base {{ $selectedVehicleType === 'suv' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Arazi, SUV & Pick-up') }}</span>
                        </button>

                        <!-- 4. Motosiklet -->
                        <button type="button" data-type="motorcycle"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'motorcycle' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-bicycle text-base {{ $selectedVehicleType === 'motorcycle' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Motosiklet & Skuter') }}</span>
                        </button>

                        <!-- 5. Ticari Nəqliyyat -->
                        <button type="button" data-type="commercial"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'commercial' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-truck text-base {{ $selectedVehicleType === 'commercial' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Ticari Araçlar') }}</span>
                        </button>

                        <!-- 6. Klasik Araçlar -->
                        <button type="button" data-type="classic"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'classic' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-gem text-base {{ $selectedVehicleType === 'classic' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Klasik Araçlar') }}</span>
                        </button>

                        <!-- 7. Qəzalı & Parçalıq -->
                        <button type="button" data-type="damaged"
                                class="vehicle-type-btn shrink-0 min-w-[130px] sm:min-w-0 sm:shrink h-11 px-3 sm:px-2.5 rounded-2xl border text-center flex items-center justify-center gap-2 cursor-pointer group {{ $selectedVehicleType === 'damaged' ? 'border-[var(--primary)] bg-orange-50/80 text-[var(--primary)] font-bold shadow-xs ring-1 ring-[var(--primary)]' : 'border-gray-200/90 bg-white hover:border-gray-300 hover:bg-gray-50 text-gray-600 hover:text-gray-900 shadow-2xs font-medium' }}">
                            <i class="bi bi-tools text-base {{ $selectedVehicleType === 'damaged' ? 'text-[var(--primary)] scale-110' : 'text-gray-400 group-hover:text-gray-700' }}"></i>
                            <span class="text-xs sm:text-[13px] truncate">{{ __('Hasarlı & Parçalık') }}</span>
                        </button>

                        <input type="hidden" name="vehicle_type" id="vehicleTypeInput" value="{{ $selectedVehicleType }}">
                    </div>

                    <!-- 2-ROW PRIMARY FILTER CONTAINER -->
                    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm space-y-3.5">
                        
                        <!-- ROW 1: Marka | Model | Condition Segment (Hidden on mobile) | Şəhər -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            
                            <!-- 1. Marka -->
                            <div class="relative">
                                <select name="brand_id" id="brandSelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">{{ __('car.brand') }}</option>
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
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9 {{ count($models) == 0 ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <option value="">{{ __('car.model') }}</option>
                                    @foreach($models as $m)
                                        <option value="{{ $m->id }}" {{ request('model_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            <!-- 3. Condition Segment: [ Yeni | Sürülmüş ] (Hidden on mobile/tablet, shown on desktop) -->
                            @php
                                $currentCondition = request('condition');
                            @endphp
                            <div class="hidden lg:flex h-11 border border-gray-200/90 rounded-xl overflow-hidden bg-white p-0.5 select-none" id="conditionSegmentGroup">
                                <button type="button" data-condition="new"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg {{ $currentCondition === 'new' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                    {{ __('Sıfır (0 km)') }}
                                </button>
                                <button type="button" data-condition="used"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg border-l border-gray-100 {{ $currentCondition === 'used' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                    {{ __('İkinci El') }}
                                </button>
                            </div>
                            <input type="hidden" name="condition" id="conditionInput" value="{{ $currentCondition }}">

                            <!-- 4. Şəhər -->
                            <div class="relative">
                                <select name="city_id" id="citySelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">{{ __('listing.select_city') }}</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ request('city_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->getTrans('name') }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- ROW 2: [Qiymət min | maks] | Ban növü | [İl min | maks] (Hidden on mobile) | Actions -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3 items-center">
                            
                            <!-- 1. Qiymət min | maks (Dual Input Box) -->
                            <div class="flex items-center h-11 border border-gray-200/90 rounded-xl bg-white overflow-hidden w-full focus-within:border-[var(--primary)] focus-within:ring-1 focus-within:ring-[var(--primary)]">
                                <input type="number" name="price_min" id="priceMinInput" value="{{ request('price_min') }}" placeholder="{{ __('listing.min_price') }}"
                                       class="w-1/2 px-3 h-full bg-transparent text-sm text-gray-800 outline-none placeholder-gray-400">
                                <div class="h-6 w-px bg-gray-200 shrink-0"></div>
                                <input type="number" name="price_max" id="priceMaxInput" value="{{ request('price_max') }}" placeholder="{{ __('listing.max_price') }}"
                                       class="w-1/2 px-3 h-full bg-transparent text-sm text-gray-800 outline-none placeholder-gray-400">
                            </div>

                            <!-- 2. Ban növü -->
                            <div class="relative">
                                <select name="body_type_id" id="bodyTypeSelect"
                                        class="w-full h-11 px-4 bg-white border border-gray-200/90 rounded-xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:ring-1 focus:ring-[var(--primary)] cursor-pointer appearance-none pr-9">
                                    <option value="">{{ __('listing.body_type') }}</option>
                                    @foreach($bodyTypes as $bt)
                                        <option value="{{ $bt->id }}" {{ request('body_type_id') == $bt->id ? 'selected' : '' }} data-applicable-types="{{ json_encode($bt->applicable_types ?? []) }}">
                                            {{ $bt->localized_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            </div>

                            <!-- 3. İl min | maks (Dual Select Box) (Hidden on mobile/tablet, shown on desktop) -->
                            <div class="hidden lg:flex items-center h-11 border border-gray-200/90 rounded-xl bg-white overflow-hidden w-full focus-within:border-[var(--primary)] focus-within:ring-1 focus-within:ring-[var(--primary)]">
                                <div class="relative w-1/2 h-full">
                                    <select name="year_min" id="yearMinSelect"
                                            class="w-full h-full pl-3 pr-6 bg-transparent text-sm text-gray-800 outline-none cursor-pointer appearance-none">
                                        <option value="">{{ __('listing.min_year') }}</option>
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
                                        <option value="">{{ __('listing.max_year') }}</option>
                                        @for($y = (int)date('Y') + 1; $y >= 1980; $y--)
                                            <option value="{{ $y }}" {{ request('year_max') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- 4. Actions: Ətraflı & Elanları göstər -->
                            <div class="flex items-center gap-2 w-full">
                                <button type="button" id="openFilterMoreBtn" title="{{ __('listing.more_filters') }}"
                                        class="h-11 flex-1 px-4 bg-white hover:bg-gray-50 border border-gray-200/90 text-gray-700 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-2xs cursor-pointer">
                                    <i class="bi bi-sliders text-sm text-[var(--primary)]"></i>
                                    <span>{{ __('listing.more') }}</span>
                                </button>
                                <button type="submit"
                                        class="h-11 px-5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-xl text-xs sm:text-sm font-bold shadow-sm flex items-center justify-center gap-1.5 shrink-0 cursor-pointer">
                                    <i class="bi bi-search text-xs"></i>
                                    <span>{{ __('listing.search') }}</span>
                                </button>
                            </div>

                        </div>

                    </div>
                </form>

                <!-- Results Header Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 mt-6 mb-4">
                    <div class="flex items-baseline gap-2">
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">{{ __('Araçlar') }}</h2>
                        <span id="carsCountBadge" class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                            {{ $cars->total() }} {{ __('listing.show_properties_count') }}
                        </span>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="carSortSelect" class="text-xs font-semibold text-gray-500 hidden sm:inline-block">{{ __('listing.sort') }}:</label>
                        <div class="relative">
                            <select name="sort" id="carSortSelect" form="carFilterForm"
                                    class="h-9 pl-3 pr-8 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 outline-none focus:border-[var(--primary)] cursor-pointer appearance-none shadow-2xs">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('listing.sort_latest') }}</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('listing.sort_price_asc') }}</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('listing.sort_price_desc') }}</option>
                                <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>{{ __('listing.sort_year_desc') }}</option>
                                <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>{{ __('listing.sort_year_asc') }}</option>
                                <option value="mileage_asc" {{ request('sort') == 'mileage_asc' ? 'selected' : '' }}>{{ __('listing.sort_mileage_asc') }}</option>
                                <option value="mileage_desc" {{ request('sort') == 'mileage_desc' ? 'selected' : '' }}>{{ __('listing.sort_mileage_desc') }}</option>
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

            // Modal elements
            const modal = document.getElementById('filterMoreModal');
            const openModalBtn = document.getElementById('openFilterMoreBtn');
            const closeModalBtn = document.getElementById('closeFilterMoreBtn');
            const applyModalBtn = document.getElementById('applyModalFiltersBtn');
            const clearModalBtn = document.getElementById('clearModalFiltersBtn');

            const modalVehicleType = document.getElementById('modal_vehicle_type');
            const modalBrandSelect = document.getElementById('modal_brand_select');
            const modalModelSelect = document.getElementById('modal_model_select');
            const modalCitySelect = document.getElementById('modal_city_select');
            const modalPriceMin = document.getElementById('modal_price_min');
            const modalPriceMax = document.getElementById('modal_price_max');
            const modalYearMin = document.getElementById('modal_year_min');
            const modalYearMax = document.getElementById('modal_year_max');

            // Cache options
            const allBrandOptions = brandSelect ? Array.from(brandSelect.querySelectorAll('option')) : [];
            const allModalBrandOptions = modalBrandSelect ? Array.from(modalBrandSelect.querySelectorAll('option')) : [];
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

                // 1.1 Filter Modal Brand Select
                if (modalBrandSelect) {
                    const currentModalBrandVal = modalBrandSelect.value;
                    modalBrandSelect.innerHTML = '';
                    let isCurrentModalBrandValid = false;

                    allModalBrandOptions.forEach(opt => {
                        if (!opt.value) {
                            modalBrandSelect.appendChild(opt.cloneNode(true));
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
                            if (opt.value === currentModalBrandVal) {
                                cloned.selected = true;
                                isCurrentModalBrandValid = true;
                            }
                            modalBrandSelect.appendChild(cloned);
                        }
                    });

                    if (!isCurrentModalBrandValid && currentModalBrandVal) {
                        modalBrandSelect.value = '';
                        if (modalModelSelect) {
                            modalModelSelect.innerHTML = '<option value="">Model</option>';
                            modalModelSelect.disabled = true;
                            modalModelSelect.classList.add('opacity-60', 'cursor-not-allowed');
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
                                placeholder.textContent = @json(__('car.motorcycle_type'));
                            } else {
                                placeholder.textContent = @json(__('listing.body_type'));
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
                            chip.classList.remove('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                            chip.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                            const icon = chip.querySelector('i');
                            if (icon) {
                                icon.classList.remove('text-[#ca1016]');
                                icon.classList.add('text-transparent');
                            }
                        }
                    }
                });

                const modalBodyTypeLabel = document.getElementById('modalBodyTypeLabelText');
                if (modalBodyTypeLabel) {
                    modalBodyTypeLabel.textContent = isMotorcycle ? @json(__('car.motorcycle_type')) : @json(__('listing.body_type'));
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

            // 0. Vehicle Type Category Cards Click
            vehicleTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const val = this.dataset.type;
                    if (vehicleTypeInput) vehicleTypeInput.value = val;

                    vehicleTypeBtns.forEach(b => {
                        b.classList.remove('border-[var(--primary)]', 'bg-orange-50/80', 'text-[var(--primary)]', 'font-bold', 'ring-1', 'ring-[var(--primary)]');
                        b.classList.add('border-gray-200/90', 'bg-white', 'text-gray-600', 'font-medium');
                        const icon = b.querySelector('i');
                        if (icon) {
                            icon.classList.remove('text-[var(--primary)]', 'scale-110');
                            icon.classList.add('text-gray-400');
                        }
                    });

                    this.classList.add('border-[var(--primary)]', 'bg-orange-50/80', 'text-[var(--primary)]', 'font-bold', 'ring-1', 'ring-[var(--primary)]');
                    this.classList.remove('border-gray-200/90', 'bg-white', 'text-gray-600', 'font-medium');
                    const activeIcon = this.querySelector('i');
                    if (activeIcon) {
                        activeIcon.classList.add('text-[var(--primary)]', 'scale-110');
                        activeIcon.classList.remove('text-gray-400');
                    }

                    filterCategoryOptions(val);
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
                    modelSelect.innerHTML = '<option value="">' + @json(__('listing.loading')) + '</option>';
                    modelSelect.disabled = true;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
                        modelSelect.disabled = true;
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        submitCarFilter();
                        return;
                    }

                    try {
                        const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                        const data = await res.json();
                        
                        if (data.success && data.models) {
                            modelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
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
                        modelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
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

            // 5. Modal Filter Controls, Syncing & Interactive Chips
            function setRadioChipState(name, value) {
                modal.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                    const isMatch = (r.value === value) || (!value && (r.value === '' || r.value === 'all'));
                    r.checked = isMatch;
                    const chip = r.closest('.modal-radio-chip');
                    if (chip) {
                        chip.classList.toggle('border-[#ca1016]', isMatch);
                        chip.classList.toggle('bg-red-50', isMatch);
                        chip.classList.toggle('text-[#ca1016]', isMatch);
                        chip.classList.toggle('font-bold', isMatch);
                        chip.classList.toggle('border-gray-200', !isMatch);
                        chip.classList.toggle('bg-white', !isMatch);
                        chip.classList.toggle('text-gray-700', !isMatch);
                        const icon = chip.querySelector('i');
                        if (icon) {
                            if (icon.classList.contains('bi-check')) {
                                icon.classList.toggle('text-[#ca1016]', isMatch);
                                icon.classList.toggle('text-transparent', !isMatch);
                            } else {
                                icon.classList.toggle('text-[#ca1016]', isMatch);
                                icon.classList.toggle('text-gray-300', !isMatch);
                            }
                        }
                    }
                });
            }

            function syncPageToModal() {
                if (!modal) return;

                // 1. Vehicle Type
                if (modalVehicleType && vehicleTypeInput) {
                    modalVehicleType.value = vehicleTypeInput.value || 'all';
                    filterCategoryOptions(modalVehicleType.value);
                }

                // 2. Ad Type
                const currentAdType = (adTypeInput ? adTypeInput.value : '') || 'all';
                setRadioChipState('modal_ad_type_radio', currentAdType);

                // 3. Condition
                const currentCond = conditionInput ? conditionInput.value : '';
                setRadioChipState('modal_condition_radio', currentCond);

                // 4. Brand & Model
                if (modalBrandSelect && brandSelect) {
                    modalBrandSelect.value = brandSelect.value;
                    if (modalModelSelect && modelSelect) {
                        modalModelSelect.innerHTML = modelSelect.innerHTML;
                        modalModelSelect.value = modelSelect.value;
                        modalModelSelect.disabled = modelSelect.disabled;
                        if (modelSelect.disabled) {
                            modalModelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        } else {
                            modalModelSelect.classList.remove('opacity-60', 'cursor-not-allowed');
                        }
                    }
                }

                // 5. City
                if (modalCitySelect && citySelect) {
                    modalCitySelect.value = citySelect.value;
                }

                // 6. Price
                if (modalPriceMin && priceMinInput) modalPriceMin.value = priceMinInput.value;
                if (modalPriceMax && priceMaxInput) modalPriceMax.value = priceMaxInput.value;

                // 7. Year
                if (modalYearMin && yearMinSelect) modalYearMin.value = yearMinSelect.value;
                if (modalYearMax && yearMaxSelect) modalYearMax.value = yearMaxSelect.value;

                // 8. Body Type
                if (bodyTypeSelect) {
                    setRadioChipState('body_type_id', bodyTypeSelect.value);
                }
            }

            function syncModalToPage() {
                if (!modal) return;

                // 1. Vehicle Type
                if (modalVehicleType && vehicleTypeInput) {
                    const newVType = modalVehicleType.value || 'all';
                    vehicleTypeInput.value = newVType;
                    vehicleTypeBtns.forEach(b => {
                        const isMatch = b.dataset.type === newVType;
                        b.classList.toggle('border-[var(--primary)]', isMatch);
                        b.classList.toggle('bg-orange-50/80', isMatch);
                        b.classList.toggle('text-[var(--primary)]', isMatch);
                        b.classList.toggle('font-bold', isMatch);
                        b.classList.toggle('ring-1', isMatch);
                        b.classList.toggle('ring-[var(--primary)]', isMatch);
                        b.classList.toggle('border-gray-200/90', !isMatch);
                        b.classList.toggle('bg-white', !isMatch);
                        b.classList.toggle('text-gray-600', !isMatch);
                        b.classList.toggle('font-medium', !isMatch);
                        const icon = b.querySelector('i');
                        if (icon) {
                            icon.classList.toggle('text-[var(--primary)]', isMatch);
                            icon.classList.toggle('scale-110', isMatch);
                            icon.classList.toggle('text-gray-400', !isMatch);
                        }
                    });
                    filterCategoryOptions(newVType);
                }

                // 2. Ad Type
                const checkedAdType = modal.querySelector('input[name="modal_ad_type_radio"]:checked');
                const newAdType = checkedAdType ? checkedAdType.value : 'all';
                if (adTypeInput) adTypeInput.value = newAdType;
                dealTypeBtns.forEach(b => {
                    const isMatch = b.dataset.value === newAdType;
                    b.classList.toggle('bg-white', isMatch);
                    b.classList.toggle('text-[var(--primary)]', isMatch);
                    b.classList.toggle('shadow-sm', isMatch);
                    b.classList.toggle('text-gray-600', !isMatch);
                });

                // 3. Condition
                const checkedCond = modal.querySelector('input[name="modal_condition_radio"]:checked');
                const newCond = checkedCond ? checkedCond.value : '';
                if (conditionInput) conditionInput.value = newCond;
                conditionBtns.forEach(b => {
                    const isMatch = b.dataset.condition === newCond && newCond !== '';
                    b.classList.toggle('bg-[#ca1016]', isMatch);
                    b.classList.toggle('text-white', isMatch);
                    b.classList.toggle('shadow-xs', isMatch);
                    b.classList.toggle('text-gray-600', !isMatch);
                });

                // 4. Brand & Model
                if (modalBrandSelect && brandSelect) {
                    const isBrandChanged = brandSelect.value !== modalBrandSelect.value;
                    brandSelect.value = modalBrandSelect.value;
                    if (isBrandChanged && modalModelSelect && modelSelect) {
                        modelSelect.innerHTML = modalModelSelect.innerHTML;
                        modelSelect.value = modalModelSelect.value;
                        modelSelect.disabled = modalModelSelect.disabled;
                        if (modelSelect.disabled) {
                            modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        } else {
                            modelSelect.classList.remove('opacity-60', 'cursor-not-allowed');
                        }
                    } else if (modalModelSelect && modelSelect) {
                        modelSelect.value = modalModelSelect.value;
                    }
                }

                // 5. City
                if (modalCitySelect && citySelect) {
                    citySelect.value = modalCitySelect.value;
                }

                // 6. Price
                if (modalPriceMin && priceMinInput) priceMinInput.value = modalPriceMin.value;
                if (modalPriceMax && priceMaxInput) priceMaxInput.value = modalPriceMax.value;

                // 7. Year
                if (modalYearMin && yearMinSelect) yearMinSelect.value = modalYearMin.value;
                if (modalYearMax && yearMaxSelect) yearMaxSelect.value = modalYearMax.value;

                // 8. Body Type
                const checkedBody = modal.querySelector('input[name="body_type_id"]:checked');
                if (bodyTypeSelect) {
                    bodyTypeSelect.value = checkedBody ? checkedBody.value : '';
                }
            }

            function openModal() {
                if (!modal) return;
                syncPageToModal();
                modal.classList.remove('hidden');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
            }
            window.closeModal = closeModal;

            if (openModalBtn) openModalBtn.addEventListener('click', openModal);
            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });

                // Modal vehicle type change
                if (modalVehicleType) {
                    modalVehicleType.addEventListener('change', function() {
                        filterCategoryOptions(this.value);
                    });
                }

                // Modal brand select change
                if (modalBrandSelect && modalModelSelect) {
                    modalBrandSelect.addEventListener('change', async function() {
                        const brandId = this.value;
                        modalModelSelect.innerHTML = '<option value="">' + @json(__('listing.loading')) + '</option>';
                        modalModelSelect.disabled = true;

                        if (!brandId) {
                            modalModelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
                            modalModelSelect.disabled = true;
                            modalModelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                            return;
                        }

                        try {
                            const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                            const data = await res.json();
                            
                            if (data.success && data.models) {
                                modalModelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
                                data.models.forEach(m => {
                                    const opt = document.createElement('option');
                                    opt.value = m.id;
                                    opt.textContent = m.name;
                                    modalModelSelect.appendChild(opt);
                                });
                                modalModelSelect.disabled = false;
                                modalModelSelect.classList.remove('opacity-60', 'cursor-not-allowed');
                            }
                        } catch (err) {
                            console.error('Error fetching models in modal:', err);
                            modalModelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
                            modalModelSelect.disabled = false;
                        }
                    });
                }

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
                                parent.classList.remove('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                                parent.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                                const icon = parent.querySelector('i');
                                if (icon) {
                                    if (icon.classList.contains('bi-check')) {
                                        icon.classList.remove('text-[#ca1016]');
                                        icon.classList.add('text-transparent');
                                    } else {
                                        icon.classList.remove('text-[#ca1016]');
                                        icon.classList.add('text-gray-300');
                                    }
                                }
                            }
                        });

                        // Toggle on if not already checked
                        if (!isCurrentlyChecked) {
                            input.checked = true;
                            this.classList.add('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                            this.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                            const icon = this.querySelector('i');
                            if (icon) {
                                if (icon.classList.contains('bi-check')) {
                                    icon.classList.remove('text-transparent');
                                    icon.classList.add('text-[#ca1016]');
                                } else {
                                    icon.classList.remove('text-gray-300');
                                    icon.classList.add('text-[#ca1016]');
                                }
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
                                this.classList.add('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                                this.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                            } else {
                                this.classList.remove('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                                this.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                            }
                        }, 10);
                    });
                });
            }

            if (applyModalBtn) {
                applyModalBtn.addEventListener('click', function() {
                    syncModalToPage();
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
                        modal.querySelectorAll('select').forEach(s => s.value = '');
                        modal.querySelectorAll('.modal-radio-chip').forEach(chip => {
                            chip.classList.remove('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                            chip.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                            const icon = chip.querySelector('i');
                            if (icon) {
                                if (icon.classList.contains('bi-check')) {
                                    icon.classList.remove('text-[#ca1016]');
                                    icon.classList.add('text-transparent');
                                } else {
                                    icon.classList.remove('text-[#ca1016]');
                                    icon.classList.add('text-gray-300');
                                }
                            }
                        });
                        modal.querySelectorAll('.modal-check-chip').forEach(chip => {
                            chip.classList.remove('border-[#ca1016]', 'bg-red-50', 'text-[#ca1016]', 'font-bold');
                            chip.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                        });
                    }
                    if (brandSelect) brandSelect.value = '';
                    if (modelSelect) {
                        modelSelect.innerHTML = '<option value="">' + @json(__('car.model')) + '</option>';
                        modelSelect.disabled = true;
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                    }
                    if (citySelect) citySelect.value = '';
                    if (priceMinInput) priceMinInput.value = '';
                    if (priceMaxInput) priceMaxInput.value = '';
                    if (yearMinSelect) yearMinSelect.value = '';
                    if (yearMaxSelect) yearMaxSelect.value = '';
                    if (conditionInput) conditionInput.value = '';
                    conditionBtns.forEach(b => {
                        b.classList.remove('bg-[#ca1016]', 'text-white', 'shadow-xs');
                        b.classList.add('text-gray-600');
                    });
                    if (bodyTypeSelect) bodyTypeSelect.value = '';
                    if (vehicleTypeInput) vehicleTypeInput.value = 'all';
                    vehicleTypeBtns.forEach(b => {
                        const isAll = b.dataset.type === 'all';
                        b.classList.toggle('border-[var(--primary)]', isAll);
                        b.classList.toggle('bg-orange-50/80', isAll);
                        b.classList.toggle('text-[var(--primary)]', isAll);
                        b.classList.toggle('font-bold', isAll);
                        b.classList.toggle('ring-1', isAll);
                        b.classList.toggle('ring-[var(--primary)]', isAll);
                        b.classList.toggle('border-gray-200/90', !isAll);
                        b.classList.toggle('bg-white', !isAll);
                        b.classList.toggle('text-gray-600', !isAll);
                        b.classList.toggle('font-medium', !isAll);
                    });
                    if (adTypeInput) adTypeInput.value = 'all';
                    dealTypeBtns.forEach(b => {
                        const isAll = b.dataset.value === 'all';
                        b.classList.toggle('bg-white', isAll);
                        b.classList.toggle('text-[var(--primary)]', isAll);
                        b.classList.toggle('shadow-sm', isAll);
                        b.classList.toggle('text-gray-600', !isAll);
                    });
                    filterCategoryOptions('all');
                    closeModal();
                    submitCarFilter();
                });
            }

            // 6. AJAX form submission and live updates
            async function submitCarFilter() {
                const formData = new FormData(form);
                
                if (sortSelect && sortSelect.value && sortSelect.value !== 'latest') {
                    formData.set('sort', sortSelect.value);
                }
                
                // Append modal form fields into form data
                if (modal) {
                    modal.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked').forEach(input => {
                        if (input.name === 'modal_ad_type_radio' || input.name === 'modal_condition_radio') return;
                        if (input.value) {
                            formData.set(input.name, input.value);
                        }
                    });
                    modal.querySelectorAll('input[type="number"]').forEach(input => {
                        if (input.id === 'modal_price_min' || input.id === 'modal_price_max') return;
                        if (input.value) {
                            formData.set(input.name, input.value);
                        } else {
                            formData.delete(input.name);
                        }
                    });
                    modal.querySelectorAll('select').forEach(select => {
                        if (select.id === 'modal_vehicle_type' || select.id === 'modal_brand_select' || select.id === 'modal_model_select' || select.id === 'modal_city_select' || select.id === 'modal_year_min' || select.id === 'modal_year_max') return;
                        if (select.value) {
                            formData.set(select.name, select.value);
                        } else {
                            formData.delete(select.name);
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
                        countBadge.textContent = `${data.total} ` + @json(__('listing.show_properties_count'));
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

            // Ensure sort is included in formData
            if (sortSelect && sortSelect.value) {
                // handled in submitCarFilter
            }

            // Bind submit event on form to prevent native reload and use AJAX
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitCarFilter();
            });

            // Bind change on inputs
            form.querySelectorAll('select, input[type="number"]').forEach(el => {
                if (el.id !== 'brandSelect' && el.id !== 'modelSelect') {
                    el.addEventListener('change', submitCarFilter);
                }
            });

            // AJAX pagination click delegation
            const pagWrapper = document.getElementById('carsPaginationWrapper');
            if (pagWrapper) {
                pagWrapper.addEventListener('click', async function(e) {
                    const link = e.target.closest('a.pagination-link');
                    if (!link) return;
                    e.preventDefault();
                    const url = link.href;
                    if (!url) return;

                    window.history.pushState({}, '', url);

                    const wrapper = document.getElementById('carsListingWrapper');
                    const countBadge = document.getElementById('carsCountBadge');

                    if (wrapper) wrapper.classList.add('opacity-50', 'pointer-events-none');

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
                            countBadge.textContent = `${data.total} ` + @json(__('listing.show_properties_count'));
                        }
                        window.scrollTo({ top: form.offsetTop - 20, behavior: 'instant' });
                    } catch (err) {
                        console.error('Pagination AJAX error:', err);
                    } finally {
                        if (wrapper) wrapper.classList.remove('opacity-50', 'pointer-events-none');
                    }
                });
            }
        });
    </script>
@endsection
