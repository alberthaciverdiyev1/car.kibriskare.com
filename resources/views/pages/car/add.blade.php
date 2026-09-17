@extends('layouts.app')

@section('title', 'Avtomobil Elanı Yerləşdir - araba.kibriskare.com')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Avtomobil Elanı Yerləşdir</h1>
            <p class="text-sm text-gray-500 mt-1">Elanınız moderasiyadan sonra qısa müddətdə saytda yayımlanacaq.</p>
        </div>

        <form id="addCarForm" action="{{ route('add-car.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- 1. Əsas Məlumatlar -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">1</span>
                    <h2 class="text-lg font-bold text-gray-900">Əsas Parametrlər</h2>
                </div>

                <div class="space-y-4">
                    <!-- Nəqliyyat Növü (Otomobil, SUV, Motosiklet, Ticari...) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nəqliyyat Kateqoriyası *</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                            @foreach($vehicleTypes as $val => $lbl)
                                <label class="flex items-center gap-2 p-2.5 rounded-2xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none has-checked:border-[var(--primary)] has-checked:bg-orange-50/60 has-checked:font-bold">
                                    <input type="radio" name="vehicle_type" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="accent-[var(--primary)] w-3.5 h-3.5">
                                    <span class="text-xs text-gray-800 truncate">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Deal Type (Satılık / Kirayə) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Elan Növü *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            @foreach($dealTypes as $val => $lbl)
                                <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none has-checked:border-[var(--primary)] has-checked:bg-orange-50/60 has-checked:font-bold">
                                    <input type="radio" name="deal_type" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                                    <span class="text-xs sm:text-sm text-gray-800">{{ $lbl }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Marka & Model -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Marka *</label>
                            <select name="brand_id" id="formBrandSelect" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">Marka seçin</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" data-applicable-types="{{ json_encode($b->applicable_types ?? []) }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Model *</label>
                            <select name="model_id" id="formModelSelect" required disabled
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition opacity-60 cursor-not-allowed">
                                <option value="">Əvvəlcə Marka seçin</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ban Növü & Buraxılış İli & Yürüş -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label id="bodyTypeLabel" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ban Növü</label>
                            <select name="body_type_id" id="formBodyTypeSelect"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">Seçin</option>
                                @foreach($bodyTypes as $bt)
                                    <option value="{{ $bt->id }}" data-applicable-types="{{ json_encode($bt->applicable_types ?? []) }}">{{ $bt->localized_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Buraxılış İli *</label>
                            <input type="number" name="year" min="1950" max="{{ (int)date('Y') + 1 }}" value="{{ (int)date('Y') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Yürüş (km) *</label>
                            <input type="number" name="mileage" min="0" placeholder="Məs: 45000" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Texniki Spesifikasiyalar -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">2</span>
                    <h2 class="text-lg font-bold text-gray-900">Texniki Xüsusiyyətlər</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Yanacaq Növü *</label>
                        <select name="fuel_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($fuelTypes as $val => $lbl)
                                <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sürətlər Qutusu *</label>
                        <select name="transmission" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($transmissions as $val => $lbl)
                                <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="steeringWheelWrapper">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sükan İstiqaməti *</label>
                        <select name="steering_wheel" id="steeringWheelSelect" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($steeringWheels as $val => $lbl)
                                <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Mühərrik Həcmi (cc)</label>
                        <input type="number" name="engine_volume" placeholder="Məs: 1995"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Mühərrik Gücü (hp)</label>
                        <input type="number" name="engine_power" placeholder="Məs: 190"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Ötürücü</label>
                        <select name="drivetrain"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="">Seçin</option>
                            @foreach($drivetrains as $val => $lbl)
                                <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Rəng</label>
                        <input type="text" name="color" placeholder="Məs: Qara, Ağ, Mavi"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Plaka Növü *</label>
                        <select name="plate_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($plateTypes as $val => $lbl)
                                <option value="{{ $val }}" {{ $val === 'kktc' ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Vəziyyəti *</label>
                        <select name="condition" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($conditions as $val => $lbl)
                                <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">VIN Kod</label>
                        <input type="text" name="vin" placeholder="17 rəqəm/hərfli kod"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>
                </div>

                <!-- Toggles (Gömrük, Kredit, Barter, Zəmanət, Razılaşma, Metalik) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-3 border-t border-gray-100">
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_customs_cleared" value="1" checked class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">KKTC Gömrüyü Ödənilib</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_credit_available" value="1" class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">Kredit / Lizinq mümkündür</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_barter_available" value="1" class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">Barter mümkündür</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="has_warranty" value="1" class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">Zəmanəti var</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_negotiable" value="1" class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">Razılaşma payı var</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_metallic" value="1" class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">Metalik Rəng</span>
                    </label>
                </div>
            </div>bold text-gray-800">Metalik Rəng</span>
                    </label>
                </div>
            </div>

            <!-- 3. Qiymət -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">3</span>
                    <h2 class="text-lg font-bold text-gray-900">Qiymət</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Məbləğ *</label>
                        <input type="number" name="price" step="0.01" min="1" placeholder="Məs: 25000" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Valyuta *</label>
                        <select name="currency" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="GBP" selected>GBP (£) - Britaniya Funtu</option>
                            <option value="TRY">TRY (₺) - Türk Lirəsi</option>
                            <option value="EUR">EUR (€) - Avro</option>
                            <option value="USD">USD ($) - ABŞ Dolları</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 4. Təchizat və Komplektasiya -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">4</span>
                    <h2 class="text-lg font-bold text-gray-900">Təchizat və Opsiyalar</h2>
                </div>

                @foreach($features as $category => $items)
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">
                            {{ match($category) { 'comfort' => 'Komfort və Rahatlıq', 'safety' => 'Təhlükəsizlik', 'multimedia' => 'Multimedia & Audio', 'exterior' => 'Eksteryer', default => 'Digər' } }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                            @foreach($items as $f)
                                <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-100 bg-gray-50/70 hover:bg-orange-50/40 cursor-pointer transition select-none">
                                    <input type="checkbox" name="features[]" value="{{ $f->id }}" class="accent-[var(--primary)] w-4 h-4 rounded">
                                    <span class="text-xs font-semibold text-gray-800">{{ $f->localized_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 5. Şəkillər -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">5</span>
                    <h2 class="text-lg font-bold text-gray-900">Avtomobil Şəkilləri</h2>
                </div>

                <div class="border-2 border-dashed border-gray-300 hover:border-[var(--primary)] rounded-3xl p-8 text-center bg-gray-50/50 transition cursor-pointer"
                     onclick="document.getElementById('carImagesInput').click()">
                    <i class="bi bi-cloud-arrow-up text-3xl text-[var(--primary)]"></i>
                    <h3 class="font-bold text-gray-800 text-sm mt-2">Şəkilləri seçin və ya buraya sürükləyin</h3>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG və ya WEBP (Maksimum 20 şəkil)</p>
                    <input type="file" name="images[]" id="carImagesInput" multiple accept="image/*" class="hidden">
                </div>

                <div id="imagePreviewContainer" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3"></div>
            </div>

            <!-- 6. Əlaqə və Təsvir -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">6</span>
                    <h2 class="text-lg font-bold text-gray-900">Əlaqə Məlumatları və Təsvir</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Satıcı Növü *</label>
                        <select name="seller_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="owner" selected>Şəxsi Sahibindən</option>
                            <option value="dealer">Avtosalon</option>
                        </select>
                    </div>

                    @if($userSalons->count() > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Avtosalonunuz</label>
                            <select name="autosalon_id"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">Şəxsi elan kimi yerləşdir</option>
                                @foreach($userSalons as $salon)
                                    <option value="{{ $salon->id }}">{{ $salon->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Şəhər *</label>
                        <select name="city_id" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="">Şəhər seçin</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}">{{ $c->getTrans('name') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Adınız / Əlaqədar Şəxs *</label>
                        <input type="text" name="contact_name" value="{{ auth()->user()?->name }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Telefon Nömrəsi *</label>
                        <input type="tel" name="contact_phone" placeholder="+90 533 ..." required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">WhatsApp Nömrəsi</label>
                        <input type="tel" name="contact_whatsapp" placeholder="+90 533 ..."
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Əlavə Qeydlər və Təsvir</label>
                    <textarea name="description" rows="4" placeholder="Avtomobilin texniki vəziyyəti, servis qeydləri və ya əlavə məlumatları qeyd edin..."
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition"></textarea>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="sticky bottom-4 z-20">
                <div class="bg-white/95 backdrop-blur-md p-4 sm:p-5 rounded-3xl border border-gray-200 shadow-xl flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 hidden sm:block">Düyməyə klikləməklə qaydaları qəbul etmiş olursunuz.</p>
                    <button type="submit" id="submitCarBtn"
                            class="w-full sm:w-auto px-8 py-3.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white font-bold text-sm sm:text-base rounded-2xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="bi bi-check2-circle text-lg"></i>
                        <span>Elanı Yayımla</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script for Dynamic Brand->Model loading, Vehicle Type filtering & Image Previews -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const brandSelect = document.getElementById('formBrandSelect');
            const modelSelect = document.getElementById('formModelSelect');
            const bodyTypeSelect = document.getElementById('formBodyTypeSelect');
            const bodyTypeLabel = document.getElementById('bodyTypeLabel');
            const steeringWheelWrapper = document.getElementById('steeringWheelWrapper');
            const steeringWheelSelect = document.getElementById('steeringWheelSelect');
            const imagesInput = document.getElementById('carImagesInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const form = document.getElementById('addCarForm');
            const submitBtn = document.getElementById('submitCarBtn');
            const vehicleTypeRadios = document.querySelectorAll('input[name="vehicle_type"]');

            // Cache all brand and body type options
            const allBrandOptions = brandSelect ? Array.from(brandSelect.querySelectorAll('option')) : [];
            const allBodyTypeOptions = bodyTypeSelect ? Array.from(bodyTypeSelect.querySelectorAll('option')) : [];

            function filterByVehicleType(selectedType) {
                if (!selectedType) return;

                // 1. Filter Brands
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

                        if (!applicable || applicable.length === 0 || applicable.includes('all') || applicable.includes(selectedType)) {
                            const cloned = opt.cloneNode(true);
                            if (opt.value === currentBrandVal) {
                                cloned.selected = true;
                                isCurrentBrandValid = true;
                            }
                            brandSelect.appendChild(cloned);
                        }
                    });

                    if (!isCurrentBrandValid) {
                        brandSelect.value = '';
                        if (modelSelect) {
                            modelSelect.innerHTML = '<option value="">Əvvəlcə Marka seçin</option>';
                            modelSelect.disabled = true;
                            modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        }
                    }
                }

                // 2. Filter Body Types
                if (bodyTypeSelect) {
                    const currentBodyTypeVal = bodyTypeSelect.value;
                    bodyTypeSelect.innerHTML = '';
                    let isCurrentBodyTypeValid = false;

                    allBodyTypeOptions.forEach(opt => {
                        if (!opt.value) {
                            bodyTypeSelect.appendChild(opt.cloneNode(true));
                            return;
                        }
                        let applicable = [];
                        try {
                            applicable = JSON.parse(opt.getAttribute('data-applicable-types') || '[]');
                        } catch (e) {
                            applicable = [];
                        }

                        if (!applicable || applicable.length === 0 || applicable.includes('all') || applicable.includes(selectedType)) {
                            const cloned = opt.cloneNode(true);
                            if (opt.value === currentBodyTypeVal) {
                                cloned.selected = true;
                                isCurrentBodyTypeValid = true;
                            }
                            bodyTypeSelect.appendChild(cloned);
                        }
                    });

                    if (!isCurrentBodyTypeValid) {
                        bodyTypeSelect.value = '';
                    }
                }

                // 3. Update Labels & Motorcycle-specific visibility
                const isMotorcycle = selectedType === 'motorcycle';
                if (bodyTypeLabel) {
                    bodyTypeLabel.textContent = isMotorcycle ? 'Motosiklet Növü' : 'Ban Növü';
                }

                if (steeringWheelWrapper && steeringWheelSelect) {
                    if (isMotorcycle) {
                        steeringWheelWrapper.style.display = 'none';
                        steeringWheelSelect.removeAttribute('required');
                        steeringWheelSelect.value = 'right';
                    } else {
                        steeringWheelWrapper.style.display = '';
                        steeringWheelSelect.setAttribute('required', 'required');
                    }
                }
            }

            // Listen for vehicle type changes
            vehicleTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        filterByVehicleType(this.value);
                    }
                });
            });

            // Run initial filter on page load
            const checkedRadio = document.querySelector('input[name="vehicle_type"]:checked');
            if (checkedRadio) {
                filterByVehicleType(checkedRadio.value);
            }

            // 1. Dynamic Brand -> Models Loading
            if (brandSelect && modelSelect) {
                brandSelect.addEventListener('change', async function() {
                    const brandId = this.value;
                    modelSelect.innerHTML = '<option value="">Yüklənir...</option>';
                    modelSelect.disabled = true;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">Əvvəlcə Marka seçin</option>';
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        return;
                    }

                    try {
                        const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                        const data = await res.json();
                        
                        if (data.success && data.models) {
                            modelSelect.innerHTML = '<option value="">Model seçin</option>';
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
                    }
                });
            }

            // 2. Image Previews
            if (imagesInput && previewContainer) {
                imagesInput.addEventListener('change', function() {
                    previewContainer.innerHTML = '';
                    const files = Array.from(this.files);

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative aspect-[4/3] rounded-2xl overflow-hidden border border-gray-200 bg-gray-100 shadow-2xs';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                ${index === 0 ? '<span class="absolute top-1.5 left-1.5 bg-[var(--primary)] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">Əsas Şəkil</span>' : ''}
                            `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }

            // 3. Form Submit with loading
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-lg"></i> Yayımlanır...';
                });
            }
        });
    </script>
@endsection
