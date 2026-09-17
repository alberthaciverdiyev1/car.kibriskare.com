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
                    
                    <!-- Top Bar: Deal Type Tabs + Reset -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        @php
                            $selectedAdType = request('adType', request('deal_type', 'all'));
                        @endphp
                        
                        <!-- Deal Type (Hamısı / Satılıq / Günlük Kirayə / Aylıq Kirayə) -->
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
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'rent_daily' ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                Günlük Kirayə (Rent a Car)
                            </button>
                            <button type="button" data-value="rent"
                                    class="deal-type-btn px-4 sm:px-5 py-2 rounded-xl font-bold text-xs tracking-wide uppercase transition duration-200 {{ $selectedAdType === 'rent' ? 'bg-white text-[var(--primary)] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                                Aylıq Kirayə
                            </button>
                            <input type="hidden" name="adType" id="adTypeInput" value="{{ $selectedAdType }}">
                        </div>

                        <!-- Reset Button Top Right -->
                        <div class="flex items-center gap-2">
                            <button type="button" id="resetFiltersBtn" title="Filtrləri Sıfırla"
                                    class="px-3.5 py-2 bg-white border border-gray-200/90 rounded-xl hover:bg-gray-50 text-gray-600 flex items-center justify-center transition shadow-2xs">
                                <i class="bi bi-arrow-clockwise text-base"></i>
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
                                        <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
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

                            <!-- 3. Condition Segment: [ Hamısı | Yeni | Sürülmüş ] -->
                            @php
                                $currentCondition = request('condition', 'all');
                                if (empty($currentCondition)) $currentCondition = 'all';
                            @endphp
                            <div class="flex h-11 border border-gray-200/90 rounded-xl overflow-hidden bg-white p-0.5 select-none" id="conditionSegmentGroup">
                                <button type="button" data-condition="all"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg transition-colors {{ $currentCondition === 'all' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                                    Hamısı
                                </button>
                                <button type="button" data-condition="new"
                                        class="condition-btn flex-1 flex items-center justify-center font-semibold text-xs sm:text-sm rounded-lg border-l border-gray-100 transition-colors {{ $currentCondition === 'new' ? 'bg-[#ca1016] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
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
                                        <option value="{{ $bt->id }}" {{ request('body_type_id') == $bt->id ? 'selected' : '' }}>
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

                            <!-- 4. Actions: Ətraflı & Sıfırla & Axtar -->
                            <div class="flex items-center gap-2 w-full justify-end">
                                <button type="button" id="openFilterMoreBtn" title="Ətraflı axtarış"
                                        class="h-11 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200/90 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                                    <i class="bi bi-sliders text-sm"></i>
                                    <span class="hidden sm:inline">Ətraflı</span>
                                </button>
                                <button type="submit"
                                        class="h-11 flex-1 px-4 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-xl text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                                    <i class="bi bi-search"></i>
                                    <span>Elanları göstər</span>
                                </button>
                            </div>

                        </div>

                    </div>
                </form>

                <!-- Results Header Bar -->
                <div class="flex items-center justify-between mt-6 mb-4">
                    <div class="flex items-baseline gap-2">
                        <h2 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">Avtomobil Elanları</h2>
                        <span id="carsCountBadge" class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                            {{ $cars->total() }} elan
                        </span>
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
            const dealTypeBtns = document.querySelectorAll('.deal-type-btn');
            const adTypeInput = document.getElementById('adTypeInput');
            const resetBtn = document.getElementById('resetFiltersBtn');
            const conditionBtns = document.querySelectorAll('.condition-btn');
            const conditionInput = document.getElementById('conditionInput');

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

            // 2. Condition Segment (Hamısı / Yeni / Sürülmüş)
            conditionBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const cond = this.dataset.condition;
                    conditionInput.value = cond;
                    conditionBtns.forEach(b => {
                        b.classList.remove('bg-[#ca1016]', 'text-white', 'shadow-xs');
                        b.classList.add('text-gray-600');
                    });
                    this.classList.add('bg-[#ca1016]', 'text-white', 'shadow-xs');
                    this.classList.remove('text-gray-600');
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

            // 5. Modal Filter Controls
            const modal = document.getElementById('filterMoreModal');
            const modalCard = document.getElementById('filterMoreModalCard');
            const openModalBtn = document.getElementById('openFilterMoreBtn');
            const closeModalBtn = document.getElementById('closeFilterMoreBtn');
            const applyModalBtn = document.getElementById('applyModalFiltersBtn');
            const clearModalBtn = document.getElementById('clearModalFiltersBtn');

            function openModal() {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalCard.classList.remove('scale-95');
                }, 10);
            }

            function closeModal() {
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
            }

            if (applyModalBtn) {
                applyModalBtn.addEventListener('click', function() {
                    closeModal();
                    submitCarFilter();
                });
            }

            if (clearModalBtn) {
                clearModalBtn.addEventListener('click', function() {
                    modal.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                    modal.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
                    modal.querySelectorAll('input[type="number"]').forEach(n => n.value = '');
                    closeModal();
                    submitCarFilter();
                });
            }

            // 6. AJAX form submission and live updates
            async function submitCarFilter() {
                const formData = new FormData(form);
                
                // Append modal form fields into form data
                if (modal) {
                    modal.querySelectorAll('input:checked, input[type="number"]').forEach(input => {
                        if (input.value) {
                            formData.set(input.name, input.value);
                        }
                    });
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
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

            // Bind change on inputs
            form.querySelectorAll('select, input[type="number"]').forEach(el => {
                if (el.id !== 'brandSelect' && el.id !== 'modelSelect') {
                    el.addEventListener('change', submitCarFilter);
                }
            });
        });
    </script>
@endsection
