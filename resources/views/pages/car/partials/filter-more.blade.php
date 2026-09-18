<!-- Detailed Car Filter Modal -->
<div id="filterMoreModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-2 sm:p-4">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[92vh] shadow-2xl flex flex-col overflow-hidden border border-gray-200" id="filterMoreModalCard">
        
        <!-- Modal Header -->
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-800 flex items-center justify-center text-sm font-bold">
                    <i class="bi bi-sliders2"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base leading-tight">{{ __('listing.more_filters') }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('Kriterlerinizi netleştirerek aradığınız araca hızlıca ulaşın') }}</p>
                </div>
            </div>
            <button type="button" id="closeFilterMoreBtn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer" title="{{ __('Kapat') }}">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Modal Body: Clean Grouped Layout -->
        <div class="p-5 sm:p-6 space-y-6 overflow-y-auto flex-1 text-sm bg-gray-50/50">
            
            <!-- SECTION 0: Temel Araç & İlan Kriterleri -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-4 shadow-2xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="bi bi-card-checklist text-[var(--primary)]"></i>
                        {{ __('Temel Araç & İlan Kriterleri') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ __('Ana Parametreler') }}</span>
                </div>

                <!-- Row: Araç Türü & İlan Türü -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Araç Türü -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('Araç Kategorisi') }}
                        </label>
                        <div class="relative">
                            <select id="modal_vehicle_type" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-8">
                                <option value="all">{{ __('listing.all') }} ({{ __('Tüm Araçlar') }})</option>
                                <option value="car" {{ request('vehicle_type') === 'car' ? 'selected' : '' }}>{{ __('Otomobil') }}</option>
                                <option value="suv" {{ request('vehicle_type') === 'suv' ? 'selected' : '' }}>{{ __('Arazi, SUV & Pick-up') }}</option>
                                <option value="motorcycle" {{ request('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>{{ __('Motosiklet & Skuter') }}</option>
                                <option value="commercial" {{ request('vehicle_type') === 'commercial' ? 'selected' : '' }}>{{ __('Ticari Araçlar') }}</option>
                                <option value="classic" {{ request('vehicle_type') === 'classic' ? 'selected' : '' }}>{{ __('Klasik Araçlar') }}</option>
                                <option value="damaged" {{ request('vehicle_type') === 'damaged' ? 'selected' : '' }}>{{ __('Hasarlı & Parçalık') }}</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- İlan Türü -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('İlan Türü') }}
                        </label>
                        <div class="grid grid-cols-3 gap-2" id="modalAdTypeChips">
                            @php $currAdType = request('adType', request('deal_type', request('ad_type', 'all'))); @endphp
                            <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $currAdType === 'all' || !$currAdType ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                <input type="radio" name="modal_ad_type_radio" value="all" {{ $currAdType === 'all' || !$currAdType ? 'checked' : '' }} class="sr-only">
                                <span>{{ __('Hamısı') }}</span>
                            </label>
                            <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $currAdType === 'sale' ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                <input type="radio" name="modal_ad_type_radio" value="sale" {{ $currAdType === 'sale' ? 'checked' : '' }} class="sr-only">
                                <span>{{ __('listing.deal_sale') }}</span>
                            </label>
                            <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $currAdType === 'rent' ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                <input type="radio" name="modal_ad_type_radio" value="rent" {{ $currAdType === 'rent' ? 'checked' : '' }} class="sr-only">
                                <span>{{ __('listing.deal_rent') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Row: Araç Durumu (Condition: Sıfır / İkinci El) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('Araç Durumu') }}
                    </label>
                    <div class="grid grid-cols-3 gap-2" id="modalConditionChips">
                        @php $currCondition = request('condition'); @endphp
                        <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ empty($currCondition) ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                            <input type="radio" name="modal_condition_radio" value="" {{ empty($currCondition) ? 'checked' : '' }} class="sr-only">
                            <span>{{ __('Hamısı') }}</span>
                        </label>
                        <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $currCondition === 'new' ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                            <input type="radio" name="modal_condition_radio" value="new" {{ $currCondition === 'new' ? 'checked' : '' }} class="sr-only">
                            <span>{{ __('Sıfır (0 km)') }}</span>
                        </label>
                        <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $currCondition === 'used' ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                            <input type="radio" name="modal_condition_radio" value="used" {{ $currCondition === 'used' ? 'checked' : '' }} class="sr-only">
                            <span>{{ __('İkinci El') }}</span>
                        </label>
                    </div>
                </div>

                <!-- Row: Marka & Model & Şehir -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Marka -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('car.brand') }}
                        </label>
                        <div class="relative">
                            <select id="modal_brand_select" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-8">
                                <option value="">{{ __('car.brand') }} ({{ __('Hamısı') }})</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }} data-applicable-types="{{ json_encode($b->applicable_types ?? []) }}">
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('car.model') }}
                        </label>
                        <div class="relative">
                            <select id="modal_model_select" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-8 {{ count($models) == 0 ? 'opacity-60 cursor-not-allowed' : '' }}">
                                <option value="">{{ __('car.model') }} ({{ __('Hamısı') }})</option>
                                @foreach($models as $m)
                                    <option value="{{ $m->id }}" {{ request('model_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Şehir -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.city') }}
                        </label>
                        <div class="relative">
                            <select id="modal_city_select" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-8">
                                <option value="">{{ __('listing.select_city') }} ({{ __('Hamısı') }})</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}" {{ request('city_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->getTrans('name') }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Row: Fiyat Aralığı & Yıl Aralığı -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 border-t border-gray-100">
                    <!-- Fiyat -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.price') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" id="modal_price_min" value="{{ request('price_min') }}" placeholder="{{ __('listing.min_price') }}"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                            <input type="number" id="modal_price_max" value="{{ request('price_max') }}" placeholder="{{ __('listing.max_price') }}"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                        </div>
                    </div>

                    <!-- Yıl -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.year') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <select id="modal_year_min" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-7">
                                    <option value="">{{ __('listing.min_year') }}</option>
                                    @for($y = (int)date('Y') + 1; $y >= 1980; $y--)
                                        <option value="{{ $y }}" {{ request('year_min') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                            </div>
                            <div class="relative">
                                <select id="modal_year_max" class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-7">
                                    <option value="">{{ __('listing.max_year') }}</option>
                                    @for($y = (int)date('Y') + 1; $y >= 1980; $y--)
                                        <option value="{{ $y }}" {{ request('year_max') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <i class="bi bi-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- SECTION 1: Kıbrıs / Kayıt & Menşei -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-4 shadow-2xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-[var(--primary)]"></i>
                        {{ __('Kıbrıs Kayıt, Plaka & Menşei') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ __('KKTC Pazarına Özel') }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Plaka Növü -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('listing.plate_type') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($plateTypes as $val => $lbl)
                                @php $isSelected = request('plate_type') === $val; @endphp
                                <label class="modal-radio-chip flex items-center gap-2 p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                    <input type="radio" name="plate_type" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                    <i class="bi bi-check-circle text-xs shrink-0 {{ $isSelected ? 'text-[#ca1016]' : 'text-gray-300' }}"></i>
                                    <span class="truncate">{{ __($lbl) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- İthalat Menşei -->
                    @if(isset($importOrigins) && count($importOrigins) > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                {{ __('İthalat Menşei') }}
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($importOrigins as $val => $lbl)
                                    @php $isSelected = request('import_origin') === $val; @endphp
                                    <label class="modal-radio-chip flex items-center gap-1.5 p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                        <input type="radio" name="import_origin" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                        <i class="bi bi-check-circle text-xs shrink-0 {{ $isSelected ? 'text-[#ca1016]' : 'text-gray-300' }}"></i>
                                        <span class="truncate">{{ __($lbl) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SECTION 2: Kasa & Gövde Tipi -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-3 shadow-2xs" id="modalBodyTypeContainer">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="bi bi-car-front text-[var(--primary)]"></i>
                        <span id="modalBodyTypeLabelText">{{ __('listing.body_type') }}</span>
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ __('Kasa tipi seçin') }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2" id="modalBodyTypeChipsGrid">
                    @foreach($bodyTypes as $bt)
                        @php $isSelected = request('body_type_id') == $bt->id; @endphp
                        <label class="modal-radio-chip modal-body-type-chip flex items-center justify-between p-2.5 rounded-lg border cursor-pointer select-none text-xs {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}"
                                data-applicable-types="{{ json_encode($bt->applicable_types ?? []) }}">
                            <span class="truncate">{{ $bt->localized_name }}</span>
                            <input type="radio" name="body_type_id" value="{{ $bt->id }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                            <i class="bi bi-check text-sm shrink-0 {{ $isSelected ? 'text-[#ca1016]' : 'text-transparent' }}"></i>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- SECTION 3: Motor, Şanzıman & Çekiş (Teknik Özellikler) -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-4 shadow-2xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="bi bi-gear-fill text-[var(--primary)]"></i>
                        {{ __('Motor, Şanzıman & Çekiş') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Yakıt Türü -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('listing.fuel_type') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($fuelTypes as $val => $lbl)
                                @php $isSelected = request('fuel_type') === $val; @endphp
                                <label class="modal-radio-chip flex items-center gap-1.5 p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                    <input type="radio" name="fuel_type" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                    <i class="bi bi-check-circle text-xs shrink-0 {{ $isSelected ? 'text-[#ca1016]' : 'text-gray-300' }}"></i>
                                    <span class="truncate">{{ __($lbl) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Vites Türü -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('listing.transmission') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($transmissions as $val => $lbl)
                                @php $isSelected = request('transmission') === $val; @endphp
                                <label class="modal-radio-chip flex items-center gap-1.5 p-2 rounded-lg border cursor-pointer select-none text-xs font-medium {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                    <input type="radio" name="transmission" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                    <i class="bi bi-check-circle text-xs shrink-0 {{ $isSelected ? 'text-[#ca1016]' : 'text-gray-300' }}"></i>
                                    <span class="truncate">{{ __($lbl) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Çekiş & Direksiyon -->
                    <div class="space-y-3">
                        <!-- Çekiş -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                {{ __('listing.drivetrain') }}
                            </label>
                            <div class="grid grid-cols-3 gap-1.5">
                                @foreach($drivetrains as $val => $lbl)
                                    @php $isSelected = request('drivetrain') === $val; @endphp
                                    <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium text-center {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                        <input type="radio" name="drivetrain" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                        <span class="truncate">{{ __($lbl) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Direksiyon -->
                        <div id="modalSteeringWheelContainer">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                {{ __('listing.steering_wheel') }}
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($steeringWheels as $val => $lbl)
                                    @php $isSelected = request('steering_wheel') === $val; @endphp
                                    <label class="modal-radio-chip flex items-center justify-center p-2 rounded-lg border cursor-pointer select-none text-xs font-medium text-center {{ $isSelected ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                                        <input type="radio" name="steering_wheel" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                        <span class="truncate">{{ __($lbl) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motor Hacmi & Gücü Sayısal Girişleri -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.engine_volume') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="engine_volume_min" id="modal_eng_vol_min" value="{{ request('engine_volume_min') }}" placeholder="Min cc (örn. 1300)"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                            <input type="number" name="engine_volume_max" id="modal_eng_vol_max" value="{{ request('engine_volume_max') }}" placeholder="Maks cc (örn. 2500)"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.engine_power') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="engine_power_min" id="modal_eng_pwr_min" value="{{ request('engine_power_min') }}" placeholder="Min HP (örn. 100)"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                            <input type="number" name="engine_power_max" id="modal_eng_pwr_max" value="{{ request('engine_power_max') }}" placeholder="Maks HP (örn. 350)"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Kilometre & Renk -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-4 shadow-2xs">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kilometre Aralığı -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.mileage') }}
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="mileage_min" id="modal_mileage_min" value="{{ request('mileage_min') }}" placeholder="Min KM"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                            <input type="number" name="mileage_max" id="modal_mileage_max" value="{{ request('mileage_max') }}" placeholder="Maks KM"
                                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-medium focus:border-gray-400 outline-none">
                        </div>
                    </div>

                    <!-- Renk Seçimi -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            {{ __('listing.color') }}
                        </label>
                        <div class="relative">
                            <select name="color" id="modal_color"
                                    class="w-full h-9 px-3 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-gray-400 cursor-pointer appearance-none pr-8">
                                <option value="">{{ __('listing.all_colors') }}</option>
                                @foreach($colors as $val => $lbl)
                                    <option value="{{ $val }}" {{ request('color') == $val ? 'selected' : '' }}>
                                        {{ $val }} ({{ $lbl }})
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Durum, Hasar, Koçan & İlan Sahibi Filtreleri -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-200 space-y-3 shadow-2xs">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                    <span class="font-bold text-gray-900 text-sm flex items-center gap-2">
                        <i class="bi bi-shield-check text-[var(--primary)]"></i>
                        {{ __('Ekspertiz, Durum & Satış Koşulları') }}
                    </span>
                    <span class="text-xs text-gray-400 font-medium">{{ __('Hızlı Seçim') }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                    @php $isDeedReady = (bool)request('title_deed_ready'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $isDeedReady ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="title_deed_ready" value="1" {{ $isDeedReady ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('Koçan / Devre Hazır') }}</span>
                    </label>

                    @php $noHeavyDamage = (bool)request('no_heavy_damage'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $noHeavyDamage ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="no_heavy_damage" value="1" {{ $noHeavyDamage ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('Ağır Hasarsız (Pert Kayıtsız)') }}</span>
                    </label>

                    @php $noTramer = (bool)request('no_tramer'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $noTramer ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="no_tramer" value="1" {{ $noTramer ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('Tramer Kaydı Olmayan') }}</span>
                    </label>

                    @php $isBarter = (bool)request('is_barter_available'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $isBarter ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="is_barter_available" value="1" {{ $isBarter ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('listing.is_barter_available') }}</span>
                    </label>

                    @php $hasWarranty = (bool)request('has_warranty'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $hasWarranty ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="has_warranty" value="1" {{ $hasWarranty ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('listing.has_warranty') }}</span>
                    </label>

                    @php $isNegotiable = (bool)request('is_negotiable'); @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $isNegotiable ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="is_negotiable" value="1" {{ $isNegotiable ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('listing.is_negotiable') }}</span>
                    </label>

                    @php $isDealer = request('seller_type') === 'dealer'; @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $isDealer ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="seller_type" value="dealer" {{ $isDealer ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('listing.dealers_only') }}</span>
                    </label>

                    @php $isOwner = request('seller_type') === 'owner'; @endphp
                    <label class="modal-check-chip flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer select-none text-xs {{ $isOwner ? 'border-[#ca1016] bg-red-50 text-[#ca1016] font-bold' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400' }}">
                        <input type="checkbox" name="seller_type" value="owner" {{ $isOwner ? 'checked' : '' }} class="accent-[#ca1016] w-4 h-4 rounded">
                        <span class="font-medium">{{ __('listing.owners_only') }}</span>
                    </label>
                </div>
            </div>

        </div>

        <!-- Modal Footer: Clear & Apply Actions -->
        <div class="px-5 py-3.5 border-t border-gray-200 flex items-center justify-between bg-white shrink-0">
            <button type="button" id="clearModalFiltersBtn" class="text-xs font-bold text-gray-500 hover:text-gray-900 cursor-pointer">
                <i class="bi bi-arrow-counterclockwise mr-1"></i>
                {{ __('listing.clear_filters') }}
            </button>
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg cursor-pointer">
                    {{ __('İptal') }}
                </button>
                <button type="button" id="applyModalFiltersBtn" class="px-6 py-2 bg-[#ca1016] hover:bg-[#b00e13] text-white text-xs sm:text-sm font-bold rounded-lg shadow-xs cursor-pointer flex items-center gap-1.5">
                    <i class="bi bi-check2"></i>
                    {{ __('listing.apply') }}
                </button>
            </div>
        </div>
    </div>
</div>
