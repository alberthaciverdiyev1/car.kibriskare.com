@php
    $quickCities = \App\Modules\Location\Models\City::where('is_active', true)->orderBy('sort_order')->take(6)->get();
    $quickBrands = \App\Modules\Car\Models\CarBrand::where('is_popular', true)->orderBy('name')->take(12)->get();
    if ($quickBrands->isEmpty()) {
        $quickBrands = \App\Modules\Car\Models\CarBrand::orderBy('name')->take(12)->get();
    }
@endphp

<section class="py-10 bg-gray-50/50 border-t border-gray-100 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-6 flex items-center gap-2">
            <i class="bi bi-lightning-charge-fill text-[var(--primary)]"></i>
            {{ __('Populyar Axtarışlar və Kateqoriyalar') }}
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-xs text-gray-600">
            <!-- Şəhərlər üzrə -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1.5">
                    <i class="bi bi-geo-alt text-[var(--primary)]"></i>
                    {{ __('Bölgələr üzrə') }}
                </h4>
                <ul class="space-y-2">
                    @foreach($quickCities as $city)
                        <li>
                            <a href="{{ url(app()->getLocale() . '/ilanlar?city_id=' . $city->id) }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                {{ $city->getTrans('name') }} {{ __('Avtomobillər') }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar') }}" class="text-[var(--primary)] font-semibold hover:underline">
                            {{ __('Bütün Bölgələr') }} &rarr;
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Populyar Markalar 1 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1.5">
                    <i class="bi bi-car-front text-[var(--primary)]"></i>
                    {{ __('Populyar Markalar') }}
                </h4>
                <ul class="space-y-2">
                    @foreach($quickBrands->take(6) as $brand)
                        <li>
                            <a href="{{ url(app()->getLocale() . '/ilanlar?brand_id=' . $brand->id) }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Populyar Markalar 2 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1.5">
                    <i class="bi bi-star text-[var(--primary)]"></i>
                    {{ __('Digər Markalar') }}
                </h4>
                <ul class="space-y-2">
                    @foreach($quickBrands->slice(6, 6) as $brand)
                        <li>
                            <a href="{{ url(app()->getLocale() . '/ilanlar?brand_id=' . $brand->id) }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar') }}" class="text-[var(--primary)] font-semibold hover:underline">
                            {{ __('Bütün Markalar') }} &rarr;
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Xüsusi Filtrlər və Seçimlər -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm flex items-center gap-1.5">
                    <i class="bi bi-sliders text-[var(--primary)]"></i>
                    {{ __('Xüsusi Kateqoriyalar') }}
                </h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar?steering_wheel=right') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            {{ __('Sağ Sükan Avtomobillər') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar?fuel_type=electric') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            {{ __('Elektrikli Avtomobillər') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar?fuel_type=hybrid') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            {{ __('Hibrid Avtomobillər') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar?plate_type=kktc') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            {{ __('KKTC Plakalı Araçlar') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(app()->getLocale() . '/ilanlar?credit_available=1') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            {{ __('Kreditə Uyğun Avtomobillər') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(app()->getLocale() . '/avtosalonlar') }}" class="hover:text-[var(--primary)] transition flex items-center gap-1.5 font-semibold text-gray-700">
                            <i class="bi bi-building"></i>
                            {{ __('Bütün Avtosalonlar / Qalereyalar') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
