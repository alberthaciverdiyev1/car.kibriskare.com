<!-- Detailed Car Filter Modal -->
<div id="filterMoreModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl w-full max-w-4xl lg:max-w-5xl max-h-[90vh] overflow-y-auto shadow-2xl flex flex-col transform scale-95 transition-transform duration-300" id="filterMoreModalCard">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] flex items-center justify-center text-base">
                    <i class="bi bi-sliders"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-base sm:text-lg">{{ __('Ətraflı Nəqliyyat Filtrləri') }}</h3>
            </div>
            <button type="button" id="closeFilterMoreBtn" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition cursor-pointer">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 sm:p-8 space-y-7 flex-1 text-sm">
            
            <!-- 1. Plaka Növü (KKTC-yə Məxsus) -->
            <div>
                <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                    <i class="bi bi-card-heading text-[var(--primary)]"></i>
                    {{ __('Plaka / Gömrük Durumu') }}
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @foreach($plateTypes as $val => $lbl)
                        @php $isSelected = request('plate_type') === $val; @endphp
                        <label class="modal-radio-chip flex items-center gap-2 p-2.5 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                            <input type="radio" name="plate_type" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                            <i class="bi bi-check-circle text-xs shrink-0 {{ $isSelected ? 'text-[var(--primary)]' : 'text-gray-400' }}"></i>
                            <span class="truncate text-xs sm:text-sm">{{ $lbl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Ban Növü (Body Type) -->
            <div>
                <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                    <i class="bi bi-car-front text-[var(--primary)]"></i>
                    {{ __('Ban Növü (Kasa Tipi)') }}
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                    @foreach($bodyTypes as $bt)
                        @php $isSelected = request('body_type_id') == $bt->id; @endphp
                        <label class="modal-radio-chip flex items-center gap-2 p-3 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                            <input type="radio" name="body_type_id" value="{{ $bt->id }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                            <span class="truncate text-xs sm:text-sm">{{ $bt->localized_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 3. Yanacaq & Sürətlər Qutusu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Yanacaq Növü (Fuel Type) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-fuel-pump text-[var(--primary)]"></i>
                        {{ __('Yanacaq Növü') }}
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($fuelTypes as $val => $lbl)
                            @php $isSelected = request('fuel_type') === $val; @endphp
                            <label class="modal-radio-chip flex items-center gap-2 p-2.5 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                                <input type="radio" name="fuel_type" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                <span class="text-xs sm:text-sm">{{ $lbl }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Sürətlər Qutusu (Transmission) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-gear text-[var(--primary)]"></i>
                        {{ __('Sürətlər Qutusu') }}
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($transmissions as $val => $lbl)
                            @php $isSelected = request('transmission') === $val; @endphp
                            <label class="modal-radio-chip flex items-center gap-2 p-2.5 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                                <input type="radio" name="transmission" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                <span class="truncate text-xs sm:text-sm">{{ $lbl }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 4. Sükan İstiqaməti & Ötürücü (Drivetrain) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sükan İstiqaməti (Steering Wheel) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-compass text-[var(--primary)]"></i>
                        {{ __('Sükan İstiqaməti') }}
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($steeringWheels as $val => $lbl)
                            @php $isSelected = request('steering_wheel') === $val; @endphp
                            <label class="modal-radio-chip flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                                <span class="text-xs sm:text-sm font-medium">{{ $lbl }}</span>
                                <input type="radio" name="steering_wheel" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Ötürücü (Drivetrain) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-bezier2 text-[var(--primary)]"></i>
                        {{ __('Ötürücü (Çəkiş)') }}
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        @foreach($drivetrains as $val => $lbl)
                            @php $isSelected = request('drivetrain') === $val; @endphp
                            <label class="modal-radio-chip flex items-center gap-2 p-2.5 rounded-2xl border cursor-pointer transition select-none {{ $isSelected ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-white text-gray-700 hover:border-[var(--primary)] hover:bg-orange-50/20' }}">
                                <input type="radio" name="drivetrain" value="{{ $val }}" {{ $isSelected ? 'checked' : '' }} class="sr-only">
                                <span class="truncate text-xs sm:text-sm">{{ $lbl }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 5. Mühərrik Göstəriciləri (Həcm cc & Güc a.g.) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mühərrik Həcmi (cc) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-speedometer2 text-[var(--primary)]"></i>
                        {{ __('Mühərrik Həcmi (sm³ / cc)') }}
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="engine_volume_min" id="modal_eng_vol_min" value="{{ request('engine_volume_min') }}" placeholder="Min (məs: 1200)"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                        <input type="number" name="engine_volume_max" id="modal_eng_vol_max" value="{{ request('engine_volume_max') }}" placeholder="Maks (məs: 3000)"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                    </div>
                </div>

                <!-- Mühərrik Gücü (hp / a.g.) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-lightning text-[var(--primary)]"></i>
                        {{ __('Mühərrik Gücü (a.g. / hp)') }}
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="engine_power_min" id="modal_eng_pwr_min" value="{{ request('engine_power_min') }}" placeholder="Min (məs: 100)"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                        <input type="number" name="engine_power_max" id="modal_eng_pwr_max" value="{{ request('engine_power_max') }}" placeholder="Maks (məs: 400)"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                    </div>
                </div>
            </div>

            <!-- 6. Yürüş Aralığı & Rənglər -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Yürüş Aralığı (Mileage Min - Max) -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-signpost-2 text-[var(--primary)]"></i>
                        {{ __('Yürüş (km)') }}
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="mileage_min" id="modal_mileage_min" value="{{ request('mileage_min') }}" placeholder="Min km"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                        <input type="number" name="mileage_max" id="modal_mileage_max" value="{{ request('mileage_max') }}" placeholder="Maks km"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                    </div>
                </div>

                <!-- Rənglər -->
                <div>
                    <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                        <i class="bi bi-palette text-[var(--primary)]"></i>
                        {{ __('Rəng') }}
                    </label>
                    <div class="relative">
                        <select name="color" id="modal_color"
                                class="w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs sm:text-sm font-semibold text-gray-800 transition outline-none focus:border-[var(--primary)] focus:bg-white cursor-pointer appearance-none pr-9">
                            <option value="">{{ __('Bütün Rənglər') }}</option>
                            @foreach($colors as $val => $lbl)
                                <option value="{{ $val }}" {{ request('color') == $val ? 'selected' : '' }}>
                                    {{ $val }} ({{ $lbl }})
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- 7. Xüsusi Seçimlər (Barter, Kredit, Zəmanət, Razılaşma, Avtosalon) -->
            <div class="pt-5 border-t border-gray-100">
                <label class="block font-bold text-gray-900 text-sm mb-3 flex items-center gap-1.5">
                    <i class="bi bi-patch-check text-[var(--primary)]"></i>
                    {{ __('Əlavə Şərtlər və Seçimlər') }}
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @php $isBarter = (bool)request('is_barter_available'); @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $isBarter ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="is_barter_available" value="1" {{ $isBarter ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Barter mümkündür') }}</span>
                    </label>

                    @php $isCredit = (bool)request('is_credit_available'); @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $isCredit ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="is_credit_available" value="1" {{ $isCredit ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Kreditlə verilir') }}</span>
                    </label>

                    @php $hasWarranty = (bool)request('has_warranty'); @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $hasWarranty ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="has_warranty" value="1" {{ $hasWarranty ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Zəmanəti var') }}</span>
                    </label>

                    @php $isNegotiable = (bool)request('is_negotiable'); @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $isNegotiable ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="is_negotiable" value="1" {{ $isNegotiable ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Razılaşma payı var') }}</span>
                    </label>

                    @php $isDealer = request('seller_type') === 'dealer'; @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $isDealer ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="seller_type" value="dealer" {{ $isDealer ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Yalnız Avtosalonlar') }}</span>
                    </label>

                    @php $isOwner = request('seller_type') === 'owner'; @endphp
                    <label class="modal-check-chip flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition select-none {{ $isOwner ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'border-gray-200 bg-gray-50 hover:bg-orange-50/30 text-gray-800' }}">
                        <input type="checkbox" name="seller_type" value="owner" {{ $isOwner ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                        <span class="font-semibold text-xs sm:text-sm">{{ __('Yalnız Sahibindən') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/80 sticky bottom-0 z-10">
            <button type="button" id="clearModalFiltersBtn" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition underline underline-offset-4 cursor-pointer">
                {{ __('Filtrləri Təmizlə') }}
            </button>
            <div class="flex items-center gap-2">
                <button type="button" id="applyModalFiltersBtn" class="px-6 py-2.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition cursor-pointer">
                    {{ __('Tətbiq Et') }}
                </button>
            </div>
        </div>
    </div>
</div>
