@extends('layouts.app')

@php
    $isEdit = isset($car);
    $pageTitle = $isEdit ? __('İlanı Redaktə Et') : __('car.post_car_title');
@endphp

@section('title', $pageTitle . ' - araba.kibriskare.com')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $isEdit ? __('İlan məlumatlarınızı aşağıdan yeniləyə bilərsiniz.') : __('car.post_car_subtitle') }}</p>
        </div>

        <form id="addCarForm" action="{{ $isEdit ? route('cars.update', $car) : route('add-car.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- 1. Əsas Məlumatlar -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">1</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_basic') }}</h2>
                </div>

                <div class="space-y-4">
                    <!-- Nəqliyyat Növü (Otomobil, SUV, Motosiklet, Ticari...) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">{{ __('car.vehicle_category') }} *</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                            @foreach($vehicleTypes as $val => $lbl)
                                <label class="flex items-center gap-2 p-2.5 rounded-2xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none has-checked:border-[var(--primary)] has-checked:bg-orange-50/60 has-checked:font-bold">
                                    <input type="radio" name="vehicle_type" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="accent-[var(--primary)] w-3.5 h-3.5">
                                    <span class="text-xs text-gray-800 truncate">{{ __($lbl) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Deal Type (Satılık / Kirayə) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">{{ __('car.deal_type') }} *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            @foreach($dealTypes as $val => $lbl)
                                <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-gray-200 hover:border-[var(--primary)] hover:bg-orange-50/40 cursor-pointer transition select-none has-checked:border-[var(--primary)] has-checked:bg-orange-50/60 has-checked:font-bold">
                                    <input type="radio" name="deal_type" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                                    <span class="text-xs sm:text-sm text-gray-800">{{ __($lbl) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Marka & Model -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.brand') }} *</label>
                            <select name="brand_id" id="formBrandSelect" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">{{ __('car.select_brand') }}</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" data-applicable-types="{{ json_encode($b->applicable_types ?? []) }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.model') }} *</label>
                            <select name="model_id" id="formModelSelect" required disabled
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition opacity-60 cursor-not-allowed">
                                <option value="">{{ __('car.select_brand_first') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ban Növü & Buraxılış İli & Yürüş -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label id="bodyTypeLabel" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.body_type') }}</label>
                            <select name="body_type_id" id="formBodyTypeSelect"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">{{ __('car.select') }}</option>
                                @foreach($bodyTypes as $bt)
                                    <option value="{{ $bt->id }}" data-applicable-types="{{ json_encode($bt->applicable_types ?? []) }}">{{ $bt->localized_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.year') }} *</label>
                            <input type="number" name="year" min="1950" max="{{ (int)date('Y') + 1 }}" value="{{ (int)date('Y') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.mileage') }} *</label>
                            <input type="number" name="mileage" min="0" placeholder="{{ __('car.mileage_placeholder') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Texniki Spesifikasiyalar -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">2</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_technical') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.fuel_type') }} *</label>
                        <select name="fuel_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($fuelTypes as $val => $lbl)
                                <option value="{{ $val }}">{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.transmission') }} *</label>
                        <select name="transmission" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($transmissions as $val => $lbl)
                                <option value="{{ $val }}">{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="steeringWheelWrapper">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.steering_wheel') }} *</label>
                        <select name="steering_wheel" id="steeringWheelSelect" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($steeringWheels as $val => $lbl)
                                <option value="{{ $val }}">{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.engine_volume') }}</label>
                        <input type="number" name="engine_volume" placeholder="1995"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.engine_power') }}</label>
                        <input type="number" name="engine_power" placeholder="190"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.drivetrain') }}</label>
                        <select name="drivetrain"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="">{{ __('car.select') }}</option>
                            @foreach($drivetrains as $val => $lbl)
                                <option value="{{ $val }}">{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.color') }}</label>
                        <input type="text" name="color" placeholder="{{ __('car.color_placeholder') }}" value="{{ old('color', $car?->color ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.plate_type') }} *</label>
                        <select name="plate_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($plateTypes as $val => $lbl)
                                <option value="{{ $val }}" {{ old('plate_type', $car?->plate_type?->value ?? 'kktc') === $val ? 'selected' : '' }}>{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('İthalat Menşei (KKTC / İthal)') }}</label>
                        <select name="import_origin"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="">{{ __('car.select') }}</option>
                            @foreach($importOrigins as $val => $lbl)
                                <option value="{{ $val }}" {{ old('import_origin', $car?->import_origin?->value ?? '') === $val ? 'selected' : '' }}>{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.condition') }} *</label>
                        <select name="condition" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            @foreach($conditions as $val => $lbl)
                                <option value="{{ $val }}" {{ old('condition', $car?->condition?->value ?? 'used') === $val ? 'selected' : '' }}>{{ __($lbl) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('Seyrüsefer Son Geçerlilik') }}</label>
                        <input type="date" name="road_tax_valid_until" value="{{ old('road_tax_valid_until', $car?->road_tax_valid_until?->format('Y-m-d') ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('Muayene Son Geçerlilik') }}</label>
                        <input type="date" name="inspection_valid_until" value="{{ old('inspection_valid_until', $car?->inspection_valid_until?->format('Y-m-d') ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.vin_code') }}</label>
                        <input type="text" name="vin" placeholder="{{ __('car.vin_placeholder') }}" value="{{ old('vin', $car?->vin ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>
                </div>

                <!-- Toggles (Gömrük, Kredit, Barter, Zəmanət, Razılaşma, Metalik, Koçan) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 pt-3 border-t border-gray-100">
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_customs_cleared" value="1" {{ old('is_customs_cleared', $car?->is_customs_cleared ?? true) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.customs_cleared') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="title_deed_ready" value="1" {{ old('title_deed_ready', $car?->title_deed_ready ?? true) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('Koçan / Devre Hazır') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_credit_available" value="1" {{ old('is_credit_available', $car?->is_credit_available ?? false) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.is_credit_available') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_barter_available" value="1" {{ old('is_barter_available', $car?->is_barter_available ?? false) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.is_barter_available') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="has_warranty" value="1" {{ old('has_warranty', $car?->has_warranty ?? false) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.has_warranty') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_negotiable" value="1" {{ old('is_negotiable', $car?->is_negotiable ?? false) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.is_negotiable') }}</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-2xl bg-gray-50 border border-gray-200 cursor-pointer select-none">
                        <input type="checkbox" name="is_metallic" value="1" {{ old('is_metallic', $car?->is_metallic ?? false) ? 'checked' : '' }} class="accent-[var(--primary)] w-4 h-4">
                        <span class="text-xs font-semibold text-gray-800">{{ __('car.metallic_color') }}</span>
                    </label>
                </div>
            </div>

            <!-- 3. Ekspertiz, Kaporta Boya & Değişen Şeması -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">3</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Ekspertiz, Boya & Değişen Durumu') }}</h2>
                </div>

                <x-car.damage-scheme :car="$car ?? null" mode="edit" />
            </div>

            <!-- 4. Qiymət -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">4</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_pricing') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.price') }} *</label>
                        <input type="number" name="price" step="0.01" min="1" placeholder="25000" value="{{ old('price', $car?->price_gbp ?? '') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.currency') }} *</label>
                        <select name="currency" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="GBP" {{ old('currency', $car?->main_currency ?? 'GBP') === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="TRY" {{ old('currency', $car?->main_currency ?? 'GBP') === 'TRY' ? 'selected' : '' }}>TRY (₺)</option>
                            <option value="EUR" {{ old('currency', $car?->main_currency ?? 'GBP') === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="USD" {{ old('currency', $car?->main_currency ?? 'GBP') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 5. Təchizat və Komplektasiya -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">5</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_features') }}</h2>
                </div>

                @php $checkedFeatures = $selectedFeatures ?? []; @endphp
                @foreach($features as $category => $items)
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">
                            {{ match($category) { 'comfort' => __('car.comfort'), 'safety' => __('car.safety'), 'multimedia' => __('car.multimedia'), 'exterior' => __('car.exterior'), default => __('car.other') } }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                            @foreach($items as $f)
                                <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-100 bg-gray-50/70 hover:bg-orange-50/40 cursor-pointer transition select-none">
                                    <input type="checkbox" name="features[]" value="{{ $f->id }}"
                                           {{ in_array($f->id, old('features', $checkedFeatures)) ? 'checked' : '' }}
                                           class="accent-[var(--primary)] w-4 h-4 rounded">
                                    <span class="text-xs font-semibold text-gray-800">{{ $f->localized_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 6. Şəkillər -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">6</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_photos') }}</h2>
                </div>

                @if($isEdit && $car->images->count() > 0)
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Mövcud Şəkillər') }}</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3" id="existingImagesList">
                            @foreach($car->images as $img)
                                <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-100" id="img-card-{{ $img->id }}">
                                    <img src="{{ $img->thumb }}" class="w-full h-full object-cover">
                                    @if($img->is_main)
                                        <span class="absolute top-1 left-1 bg-emerald-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow">{{ __('Əsas') }}</span>
                                    @endif
                                    <button type="button" onclick="deleteExistingCarImage({{ $img->id }})"
                                            class="absolute top-1 right-1 bg-rose-600/90 hover:bg-rose-700 text-white w-6 h-6 rounded-full flex items-center justify-center transition shadow opacity-0 group-hover:opacity-100 cursor-pointer"
                                            title="{{ __('Şəkli sil') }}">
                                        <i class="bi bi-trash3 text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border-2 border-dashed border-gray-300 hover:border-[var(--primary)] rounded-3xl p-8 text-center bg-gray-50/50 transition cursor-pointer"
                     onclick="document.getElementById('carImagesInput').click()">
                    <i class="bi bi-cloud-arrow-up text-3xl text-[var(--primary)]"></i>
                    <h3 class="font-bold text-gray-800 text-sm mt-2">{{ __('car.upload_photos_click') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ __('car.photo_formats') }}</p>
                    <input type="file" name="images[]" id="carImagesInput" multiple accept="image/*" class="hidden">
                </div>

                <div id="imagePreviewContainer" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3 empty:hidden"></div>
            </div>

            <!-- 7. Əlaqə və Təsvir -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/80 shadow-2xs space-y-6">
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                    <span class="w-8 h-8 rounded-xl bg-orange-50 text-[var(--primary)] font-black flex items-center justify-center text-sm">7</span>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('car.step_contact') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.seller_type') }} *</label>
                        <select name="seller_type" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="owner" {{ old('seller_type', $car?->seller_type ?? 'owner') === 'owner' ? 'selected' : '' }}>{{ __('car.private_seller') }}</option>
                            <option value="dealer" {{ old('seller_type', $car?->seller_type ?? 'owner') === 'dealer' ? 'selected' : '' }}>{{ __('car.dealer_seller') }}</option>
                        </select>
                    </div>

                    @if($userSalons->count() > 0)
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.your_salon') }}</label>
                            <select name="autosalon_id"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                                <option value="">{{ __('car.post_as_individual') }}</option>
                                @foreach($userSalons as $salon)
                                    <option value="{{ $salon->id }}" {{ old('autosalon_id', $car?->autosalon_id ?? '') == $salon->id ? 'selected' : '' }}>{{ $salon->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.city') }} *</label>
                        <select name="city_id" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                            <option value="">{{ __('car.select_city') }}</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->id }}" {{ old('city_id', $car?->city_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->getTrans('name') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.contact_name') }} *</label>
                        <input type="text" name="contact_name" value="{{ old('contact_name', $car?->contact_name ?? auth()->user()?->name) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.contact_phone') }} *</label>
                        <input type="tel" name="contact_phone" placeholder="+90 533 ..." value="{{ old('contact_phone', $car?->contact_phone ?? '') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.whatsapp') }}</label>
                        <input type="tel" name="contact_whatsapp" placeholder="+90 533 ..." value="{{ old('contact_whatsapp', $car?->contact_whatsapp ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>
                </div>

                @if(\App\Modules\Shared\Models\SiteSetting::current()->enable_car_video)
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('Araç Tanıtım Videosu (YouTube Linki)') }}</label>
                        <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url', $car?->video_url ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">{{ __('car.description') }}</label>
                    <textarea name="description" rows="4" placeholder="{{ __('car.description_placeholder') }}"
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-medium text-gray-800 outline-none focus:border-[var(--primary)] focus:bg-white transition">{{ old('description', $car?->display_description ?? '') }}</textarea>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="sticky bottom-4 z-20">
                <div class="bg-white/95 backdrop-blur-md p-4 sm:p-5 rounded-3xl border border-gray-200 shadow-xl flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 hidden sm:block">{{ __('car.terms_notice') }}</p>
                    <button type="submit" id="submitCarBtn"
                            class="w-full sm:w-auto px-8 py-3.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white font-bold text-sm sm:text-base rounded-2xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="bi bi-check2-circle text-lg"></i>
                        <span>{{ $isEdit ? __('İlanı Yenilə') : __('car.submit_listing') }}</span>
                    </button>
                </div>
            </div>n>
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
                            modelSelect.innerHTML = '<option value="">' + @json(__('car.select_brand_first')) + '</option>';
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
                    bodyTypeLabel.textContent = isMotorcycle ? @json(__('car.motorcycle_type')) : @json(__('car.body_type'));
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
                    modelSelect.innerHTML = '<option value="">' + @json(__('listing.loading')) + '</option>';
                    modelSelect.disabled = true;

                    if (!brandId) {
                        modelSelect.innerHTML = '<option value="">' + @json(__('car.select_brand_first')) + '</option>';
                        modelSelect.classList.add('opacity-60', 'cursor-not-allowed');
                        return;
                    }

                    try {
                        const res = await fetch(`/${document.documentElement.lang || 'tr'}/api/brands/${brandId}/models`);
                        const data = await res.json();
                        
                        if (data.success && data.models) {
                            modelSelect.innerHTML = '<option value="">' + @json(__('car.select_model')) + '</option>';
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
                const mainPhotoText = @json(__('car.main_photo'));
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
                                ${index === 0 ? `<span class="absolute top-1.5 left-1.5 bg-[var(--primary)] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">${mainPhotoText}</span>` : ''}
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
                    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-lg"></i> ' + @json(__('car.submit_updating'));
                });
            }
        });

        async function deleteExistingCarImage(imageId) {
            if (!confirm(@json(__('Bu şəkli silmək istədiyinizdən əminsiniz?')))) return;
            try {
                const res = await fetch(`/api/car-images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                if (data.success) {
                    const card = document.getElementById(`img-card-${imageId}`);
                    if (card) card.remove();
                } else {
                    alert(data.message || 'Xəta baş verdi');
                }
            } catch (e) {
                alert('Xəta baş verdi.');
            }
        }
    </script>
@endsection
