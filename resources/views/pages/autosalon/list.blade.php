@extends('layouts.app')

@section('title', __('navbar.autosalons') . ' - araba.kibriskare.com')
@section('meta_description', 'Kuzey Kıbrıs (KKTC) genelindeki tüm yetkili ve güvenilir avtosalonlar, araba galerileri ve rent a car şirketleri.')

@section('content')
<div class="w-full pt-4 pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    <section class="mt-4 sm:mt-6">
        <!-- Header & Search Banner -->
        <div class="bg-gradient-to-r from-[var(--primary)] to-emerald-800 rounded-3xl p-6 sm:p-8 text-white mb-8 shadow-sm">
            <div class="max-w-3xl">
                <span class="bg-white/20 backdrop-blur-xs text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    KKTC Avtomobil Dilerləri
                </span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold mt-3 tracking-tight">
                    {{ __('navbar.autosalons') }}
                </h1>
                <p class="text-emerald-100 text-sm sm:text-base mt-2 max-w-2xl leading-relaxed">
                    Kuzey Kıbrıs'ın en prestijli avtosalonları, galerileri ve rent a car firmalarını keşfedin, güvenle araç satın alın veya kiralayın.
                </p>

                <!-- Filter / Search Form -->
                <form method="GET" action="{{ route('autosalons.index') }}" class="mt-6 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Avtosalon adı ilə axtar..."
                               class="w-full pl-11 pr-4 py-3 bg-white text-gray-900 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 shadow-xs placeholder-gray-400" />
                    </div>

                    <div class="sm:w-56">
                        <select name="city_id"
                                onchange="this.form.submit()"
                                class="w-full px-4 py-3 bg-white text-gray-800 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 shadow-xs cursor-pointer">
                            <option value="">Bütün Şəhərlər</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->getTrans('name') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="px-6 py-3 bg-neutral-900 hover:bg-black text-white font-bold rounded-2xl text-sm transition shadow-sm shrink-0">
                        Axtar
                    </button>
                </form>
            </div>
        </div>

        <!-- Autosalons Grid -->
        @if($autosalons->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($autosalons as $salon)
                    <div class="bg-white rounded-3xl border border-gray-200/80 hover:border-[var(--primary)] overflow-hidden flex flex-col group transition-all duration-300 shadow-2xs hover:shadow-lg">
                        
                        <!-- Banner & Logo Header -->
                        <div class="relative h-36 bg-gray-100 overflow-hidden">
                            <img src="{{ $salon->banner_url }}"
                                 alt="{{ $salon->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                            <!-- Rating & Badge -->
                            <div class="absolute top-3 right-3 flex items-center gap-1.5">
                                @if($salon->is_verified)
                                    <span class="bg-emerald-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-lg shadow-sm flex items-center gap-1">
                                        <i class="bi bi-patch-check-fill text-xs"></i> Təsdiqlənmiş
                                    </span>
                                @endif
                                <span class="bg-neutral-900/80 backdrop-blur-xs text-white text-xs font-bold px-2 py-0.5 rounded-lg flex items-center gap-1">
                                    <i class="bi bi-star-fill text-amber-400 text-xs"></i> {{ $salon->rating ?? '5.0' }}
                                </span>
                            </div>

                            <!-- Logo -->
                            <div class="absolute -bottom-4 left-5 w-16 h-16 rounded-2xl bg-white p-1 shadow-md border-2 border-white overflow-hidden">
                                <img src="{{ $salon->logo_url }}" alt="{{ $salon->name }}" class="w-full h-full object-cover rounded-xl" />
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 pt-7 flex flex-col flex-1 justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-[var(--primary)] transition">
                                    <a href="{{ route('autosalons.show', $salon->slug) }}">
                                        {{ $salon->name }}
                                    </a>
                                </h3>

                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                    <i class="bi bi-geo-alt text-gray-400"></i>
                                    <span>{{ $salon->city?->getTrans('name') ?? 'Kuzey Kıbrıs' }}</span>
                                    @if($salon->address)
                                        <span>•</span>
                                        <span class="truncate">{{ Str::limit($salon->address, 30) }}</span>
                                    @endif
                                </div>

                                @if($salon->description)
                                    <p class="text-xs text-gray-600 mt-3 line-clamp-2 leading-relaxed">
                                        {{ is_array($salon->description) ? ($salon->description[app()->getLocale()] ?? $salon->description['tr'] ?? '') : $salon->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer Info & Action Button -->
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-xl">
                                    <i class="bi bi-car-front text-sm"></i>
                                    <span>{{ $salon->cars_count }} Avtomobil</span>
                                </div>

                                <a href="{{ route('autosalons.show', $salon->slug) }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-bold bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white px-4 py-2 rounded-xl transition shadow-2xs">
                                    <span>Vitrini Gör</span>
                                    <i class="bi bi-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $autosalons->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-200 p-8">
                <i class="bi bi-building-exclamation text-4xl text-gray-300"></i>
                <h3 class="text-lg font-bold text-gray-800 mt-3">Heç bir avtosalon tapılmadı</h3>
                <p class="text-sm text-gray-500 mt-1">Axtarış meyarlarını dəyişərək yenidən yoxlayın.</p>
                <a href="{{ route('autosalons.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-[var(--primary)] text-white text-xs font-bold rounded-xl">
                    Bütün Salonları Göstər
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
