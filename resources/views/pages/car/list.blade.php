@extends('layouts.app')

@section('title', 'Kuzey Kıbrıs Avtomobil Elanları - KibrisKare.com')
@section('meta_description', 'KKTC-də satılıq və kirayə avtomobillər, avtosalonlar, sağ sükan yapon və avropa maşınları.')

@section('content')
    <div class="w-full pt-4">
        @include('components.scroll-top')

        <section class="car-listing py-2">
            <div class="container mx-auto px-2 sm:px-4 text-sm">
                
                <!-- Main Car Search Form -->
                <form method="GET" action="{{ route('listing') }}" id="carFilterForm" class="space-y-4">
                    
                    <!-- Top Bar: Deal Type Tabs + Reset -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        @php
                            $selectedAdType = request('adType', request('deal_type', 'all'));
                        @endphp
                        
                        <!-- Deal Type (Satılık / Kirayə / Günlük) -->
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

                        <!-- Reset & View Controls -->
                        <div class="flex items-center gap-2">
                            <button type="button" id="resetFiltersBtn" title="Filtrləri Sıfırla"
                                    class="px-3.5 py-2 bg-white border border-gray-200/90 rounded-xl hover:bg-gray-50 text-gray-600 flex items-center justify-center transition shadow-2xs">
                                <i class="bi bi-arrow-clockwise text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Primary Filter Row (Brand, Model, Year, Price, City, More Filters) -->
                    <div class="bg-white p-3.5 sm:p-5 rounded-3xl border border-gray-200/80 shadow-sm grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        
                        <!-- 1. Marka (Brand) -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Marka</label>
                            <select name="brand_id" id="brandSelect"
                                    class="w-full px-3 py-2.5 bg-gray-50 hover:bg-gray-100/70 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 transition outline-none focus:border-[var(--primary)] focus:bg-white">
                                <option value="">Bütün Markalar</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 2. Model -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Model</label>
                            <select name="model_id" id="modelSelect"
                                    class="w-full px-3 py-2.5 bg-gray-50 hover:bg-gray-100/70 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 transition outline-none focus:border-[var(--primary)] focus:bg-white {{ count($models) == 0 ? 'opacity-60 cursor-not-allowed' : '' }}">
                                <option value="">{{ count($models) > 0 ? 'Bütün Modellər' : 'Əvvəlcə Marka seçin' }}</option>
                                @foreach($models as $m)
                                    <option value="{{ $m->id }}" {{ request('model_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. İl (Year Min - Max) -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Buraxılış İli</label>
                            <div class="flex items-center gap-1.5">
                                <input type="number" name="year_min" value="{{ request('year_min') }}" placeholder="İl min"
                                       class="w-1/2 px-2.5 py-2.5 bg-gray-50 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white">
                                <span class="text-gray-400 font-bold">-</span>
                                <input type="number" name="year_max" value="{{ request('year_max') }}" placeholder="İl maks"
                                       class="w-1/2 px-2.5 py-2.5 bg-gray-50 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white">
                            </div>
                        </div>

                        <!-- 4. Qiymət (Price Min - Max) -->
                        <div>
                            @php $currencySym = session('currency', 'GBP') === 'TRY' ? '₺' : (session('currency') === 'EUR' ? '€' : (session('currency') === 'USD' ? '$' : '£')); @endphp
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Qiymət ({{ $currencySym }})</label>
                            <div class="flex items-center gap-1.5">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min"
                                       class="w-1/2 px-2.5 py-2.5 bg-gray-50 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white">
                                <span class="text-gray-400 font-bold">-</span>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Maks"
                                       class="w-1/2 px-2.5 py-2.5 bg-gray-50 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white">
                            </div>
                        </div>

                        <!-- 5. Şəhər (City) -->
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1">Şəhər</label>
                            <select name="city_id" id="citySelect"
                                    class="w-full px-3 py-2.5 bg-gray-50 hover:bg-gray-100/70 border border-gray-200/90 rounded-xl text-xs font-semibold text-gray-800 transition outline-none focus:border-[var(--primary)] focus:bg-white">
                                <option value="">Bütün Şəhərlər</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}" {{ request('city_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->getTrans('name') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 6. Ətraflı Filtrlər və Axtar Düyməsi -->
                        <div class="flex items-end gap-2">
                            <button type="button" id="openFilterMoreBtn"
                                    class="flex-1 px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200/80 text-gray-800 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs">
                                <i class="bi bi-sliders text-sm"></i>
                                <span>Ətraflı</span>
                            </button>
                            <button type="submit"
                                    class="px-4 py-2.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-1">
                                <i class="bi bi-search"></i>
                            </button>
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

            // 1. Dynamic Brand -> Models AJAX loading
            if (brandSelect && modelSelect) {
                brandSelect.addEventListener('change', async function() {
                    const brandId = this.value;
                    modelSelect.innerHTML = '<option value="">Yüklənir...</option>';
                    modelSelect.disabled = true;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">Əvvəlcə Marka seçin</option>';
                        modelSelect.disabled = true;
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        submitCarFilter();
                        return;
                    }

                    try {
                        const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                        const data = await res.json();
                        
                        if (data.success && data.models) {
                            modelSelect.innerHTML = '<option value="">Bütün Modellər</option>';
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
                        modelSelect.innerHTML = '<option value="">Bütün Modellər</option>';
                        modelSelect.disabled = false;
                    }

                    submitCarFilter();
                });

                modelSelect.addEventListener('change', submitCarFilter);
            }

            // 2. Deal Type Toggle
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

            // 3. Reset Button
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    window.location.href = `/${document.documentElement.lang || 'tr'}/ilanlar`;
                });
            }

            // 4. Modal Filter Controls
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

            // 5. AJAX form submission and live updates
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
