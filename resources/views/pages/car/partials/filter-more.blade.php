<!-- Detailed Car Filter Modal -->
<div id="filterMoreModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl flex flex-col transform scale-95 transition-transform duration-300" id="filterMoreModalCard">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white/95 backdrop-blur-md z-10">
            <div class="flex items-center gap-2">
                <i class="bi bi-sliders text-[var(--primary)] text-lg"></i>
                <h3 class="font-bold text-gray-900 text-base sm:text-lg">Ətraflı Avtomobil Filtrləri</h3>
            </div>
            <button type="button" id="closeFilterMoreBtn" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center transition">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 flex-1 text-sm">
            
            <!-- Ban Növü (Body Type) -->
            <div>
                <label class="block font-bold text-gray-800 mb-2.5">Ban Növü</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($bodyTypes as $bt)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none {{ request('body_type_id') == $bt->id ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'text-gray-700' }}">
                            <input type="radio" name="body_type_id" value="{{ $bt->id }}" {{ request('body_type_id') == $bt->id ? 'checked' : '' }} class="sr-only">
                            <i class="bi bi-car-front text-gray-400 text-sm"></i>
                            <span class="truncate">{{ $bt->localized_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Yanacaq Növü (Fuel Type) -->
            <div>
                <label class="block font-bold text-gray-800 mb-2.5">Yanacaq Növü</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($fuelTypes as $val => $lbl)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none {{ request('fuel_type') === $val ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'text-gray-700' }}">
                            <input type="radio" name="fuel_type" value="{{ $val }}" {{ request('fuel_type') === $val ? 'checked' : '' }} class="sr-only">
                            <i class="bi bi-fuel-pump text-gray-400 text-sm"></i>
                            <span>{{ $lbl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Sürətlər Qutusu (Transmission) -->
            <div>
                <label class="block font-bold text-gray-800 mb-2.5">Sürətlər Qutusu</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($transmissions as $val => $lbl)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none {{ request('transmission') === $val ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'text-gray-700' }}">
                            <input type="radio" name="transmission" value="{{ $val }}" {{ request('transmission') === $val ? 'checked' : '' }} class="sr-only">
                            <i class="bi bi-gear text-gray-400 text-sm"></i>
                            <span class="truncate">{{ $lbl }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Sükan İstiqaməti (Steering Wheel) -->
            <div>
                <label class="block font-bold text-gray-800 mb-2.5">Sükan İstiqaməti</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($steeringWheels as $val => $lbl)
                        <label class="flex items-center justify-between p-3 rounded-xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none {{ request('steering_wheel') === $val ? 'border-[var(--primary)] bg-orange-50/60 font-semibold text-[var(--primary)]' : 'text-gray-700' }}">
                            <span class="flex items-center gap-2">
                                <i class="bi bi-compass text-gray-400"></i>
                                <span>{{ $lbl }}</span>
                            </span>
                            <input type="radio" name="steering_wheel" value="{{ $val }}" {{ request('steering_wheel') === $val ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Yürüş Aralığı (Mileage Min - Max) -->
            <div>
                <label class="block font-bold text-gray-800 mb-2.5">Yürüş (km)</label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" name="mileage_min" value="{{ request('mileage_min') }}" placeholder="Min km (məs: 0)"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                    <input type="number" name="mileage_max" value="{{ request('mileage_max') }}" placeholder="Maks km (məs: 100,000)"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-semibold focus:border-[var(--primary)] focus:bg-white transition outline-none">
                </div>
            </div>

            <!-- Xüsusi Seçimlər (Barter, Kredit, Avtosalon) -->
            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200 cursor-pointer hover:bg-orange-50/40 transition select-none">
                    <input type="checkbox" name="is_barter_available" value="1" {{ request('is_barter_available') ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                    <span class="font-medium text-xs text-gray-800">Barter mümkündür</span>
                </label>
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200 cursor-pointer hover:bg-orange-50/40 transition select-none">
                    <input type="checkbox" name="is_credit_available" value="1" {{ request('is_credit_available') ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                    <span class="font-medium text-xs text-gray-800">Kreditlə verilir</span>
                </label>
                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200 cursor-pointer hover:bg-orange-50/40 transition select-none">
                    <input type="checkbox" name="seller_type" value="dealer" {{ request('seller_type') === 'dealer' ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4 rounded">
                    <span class="font-medium text-xs text-gray-800">Yalnız Avtosalonlar</span>
                </label>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50/80 sticky bottom-0 z-10">
            <button type="button" id="clearModalFiltersBtn" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition underline underline-offset-4">
                Filtrləri Təmizlə
            </button>
            <div class="flex items-center gap-2">
                <button type="button" id="applyModalFiltersBtn" class="px-6 py-2.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition">
                    Tətbiq Et
                </button>
            </div>
        </div>
    </div>
</div>
