@extends('layouts.app')

@section('title', $autosalon->name . ' - ' . ($autosalon->city?->getTrans('name') ?? 'KKTC') . ' | KibrisKare')
@section('meta_description', Str::limit(is_array($autosalon->description) ? ($autosalon->description['tr'] ?? '') : (string)$autosalon->description, 160))

@section('content')
<div class="w-full pt-4 pb-16">
    @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
    @include('components.scroll-top')

    <!-- Autosalon Cover & Profile Header -->
    <div class="mt-4 sm:mt-6 bg-white rounded-3xl border border-gray-200/80 shadow-2xs overflow-hidden">
        
        <!-- Banner Image -->
        <div class="relative h-48 sm:h-64 lg:h-72 bg-gray-900 overflow-hidden">
            <img src="{{ $autosalon->banner_url }}"
                 alt="{{ $autosalon->name }}"
                 class="w-full h-full object-cover opacity-90" />
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        <!-- Info Bar (Overlapping Banner) -->
        <div class="px-6 sm:px-8 pb-6 sm:pb-8 pt-0 relative">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 -mt-16 sm:-mt-20">
                
                <!-- Logo & Salon Name -->
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 sm:gap-6">
                    <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-3xl bg-white p-2 shadow-xl border-4 border-white overflow-hidden shrink-0">
                        <img src="{{ $autosalon->logo_url }}" alt="{{ $autosalon->name }}" class="w-full h-full object-cover rounded-2xl" />
                    </div>

                    <div class="text-left">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            @if($autosalon->is_verified)
                                <span class="bg-emerald-500 text-white text-xs font-bold px-2.5 py-0.5 rounded-lg shadow-sm flex items-center gap-1">
                                    <i class="bi bi-patch-check-fill text-xs"></i> {{ __('agency.official_partner') }}
                                </span>
                            @endif
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-lg">
                                {{ $cars->total() }} {{ __('listing.show_properties_count') }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $autosalon->name }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-500 mt-2">
                            <span class="flex items-center gap-1 font-medium">
                                <i class="bi bi-geo-alt text-[var(--primary)]"></i>
                                {{ $autosalon->city?->getTrans('name') ?? 'Kuzey Kıbrıs' }}
                                @if($autosalon->address) • {{ $autosalon->address }} @endif
                            </span>
                            @if($autosalon->working_hours)
                                <span class="flex items-center gap-1.5 font-medium">
                                    <i class="bi bi-clock text-[var(--primary)]"></i>
                                    <span>{{ $autosalon->working_hours }}</span>
                                    @if($autosalon->isOpenNow())
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] sm:text-[11px] font-black px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> {{ __('İNDİ AÇIQDIR') }}
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> {{ __('İNDİ BAĞLIDIR') }}
                                        </span>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact CTA Buttons -->
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    @if($autosalon->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $autosalon->whatsapp) }}"
                           target="_blank"
                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm">
                            <i class="bi bi-whatsapp text-base"></i>
                            <span>WhatsApp</span>
                        </a>
                    @endif

                    @if($autosalon->phone)
                        <a href="tel:{{ $autosalon->phone }}"
                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white text-sm font-bold shadow-sm">
                            <i class="bi bi-telephone-fill text-base"></i>
                            <span>{{ $autosalon->phone }}</span>
                        </a>
                    @endif
                </div>

            </div>

            <!-- Description & About -->
            @if($autosalon->description)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-600 leading-relaxed max-w-4xl">
                        {{ is_array($autosalon->description) ? ($autosalon->description[app()->getLocale()] ?? $autosalon->description['tr'] ?? '') : $autosalon->description }}
                    </p>
                </div>
            @endif

            <!-- Sales Consultants / Team (Feature 11) -->
            @if(!empty($autosalon->consultants) && count($autosalon->consultants) > 0)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3.5 flex items-center gap-2">
                        <i class="bi bi-people-fill text-[var(--primary)]"></i>
                        <span>{{ __('Satış Təmsilçiləri və Əlaqəli Şəxslər') }}</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($autosalon->consultants as $c)
                            @if(!empty($c['name']))
                                <div class="bg-gray-50 border border-gray-200/90 rounded-2xl p-3.5 flex flex-col justify-between gap-3 shadow-2xs">
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $c['name'] }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $c['role'] ?? __('Satış Meneceri') }}</div>
                                    </div>
                                    <div class="flex items-center gap-2 pt-2 border-t border-gray-200/70">
                                        @if(!empty($c['phone']))
                                            <a href="tel:{{ $c['phone'] }}" class="flex-1 py-1.5 px-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-800 text-xs font-bold rounded-xl flex items-center justify-center gap-1 shadow-2xs">
                                                <i class="bi bi-telephone text-[var(--primary)]"></i>
                                                <span class="truncate">{{ $c['phone'] }}</span>
                                            </a>
                                        @endif
                                        @if(!empty($c['whatsapp']))
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c['whatsapp']) }}" target="_blank" rel="noopener" class="w-8 h-8 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl flex items-center justify-center shrink-0 shadow-2xs" title="WhatsApp">
                                                <i class="bi bi-whatsapp text-xs"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Inventory / Cars Section -->
    <section class="mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="bi bi-grid-fill text-[var(--primary)]"></i>
                    <span>{{ __('agency.agency_listings') }}</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    {{ __('agency.all_listings_posted_by', ['name' => $autosalon->name]) }}
                </p>
            </div>

            <!-- Deal Type Filter Pills -->
            <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-2xl">
                <a href="{{ route('autosalons.show', $autosalon->slug) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold {{ !request('deal_type') ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('listing.all') }} ({{ $autosalon->cars_count }})
                </a>
                <a href="{{ route('autosalons.show', ['slug' => $autosalon->slug, 'deal_type' => 'sale']) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold {{ request('deal_type') === 'sale' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('listing.buy') }}
                </a>
                <a href="{{ route('autosalons.show', ['slug' => $autosalon->slug, 'deal_type' => 'rent_daily']) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold {{ request('deal_type') === 'rent_daily' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    {{ __('listing.rent') }}
                </a>
            </div>
        </div>

        @if($cars->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($cars as $car)
                    @include('components.car-card', ['car' => $car])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $cars->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border border-gray-200 p-8">
                <i class="bi bi-car-front text-4xl text-gray-300"></i>
                <h3 class="text-lg font-bold text-gray-800 mt-3">{{ __('agency.no_agency_listings') }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ __('agency.browse_other_agencies_agents') }}</p>
                <a href="{{ route('autosalons.show', $autosalon->slug) }}" class="mt-4 inline-block px-5 py-2.5 bg-[var(--primary)] text-white text-xs font-bold rounded-xl">
                    {{ __('listing.show_results') }}
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
