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
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
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
                                    <i class="bi bi-patch-check-fill text-xs"></i> Təsdiqlənmiş Diler
                                </span>
                            @endif
                            <span class="bg-amber-400 text-gray-900 text-xs font-extrabold px-2.5 py-0.5 rounded-lg flex items-center gap-1">
                                <i class="bi bi-star-fill text-xs"></i> {{ $autosalon->rating ?? '5.0' }}
                            </span>
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2.5 py-0.5 rounded-lg">
                                {{ $cars->total() }} İlan
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
                                <span class="flex items-center gap-1 font-medium">
                                    <i class="bi bi-clock text-[var(--primary)]"></i>
                                    {{ $autosalon->working_hours }}
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
                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">
                            <i class="bi bi-whatsapp text-base"></i>
                            <span>WhatsApp</span>
                        </a>
                    @endif

                    @if($autosalon->phone)
                        <a href="tel:{{ $autosalon->phone }}"
                           class="flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white text-sm font-bold shadow-sm transition">
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
        </div>
    </div>

    <!-- Inventory / Cars Section -->
    <section class="mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="bi bi-grid-fill text-[var(--primary)]"></i>
                    <span>Avtosalonun Elanları</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Bu avtosalon tərəfindən yerləşdirilmiş aktiv avtomobillər
                </p>
            </div>

            <!-- Deal Type Filter Pills -->
            <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-2xl">
                <a href="{{ route('autosalons.show', $autosalon->slug) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ !request('deal_type') ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Hamısı ({{ $autosalon->cars_count }})
                </a>
                <a href="{{ route('autosalons.show', ['slug' => $autosalon->slug, 'deal_type' => 'sale']) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('deal_type') === 'sale' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Satılıq
                </a>
                <a href="{{ route('autosalons.show', ['slug' => $autosalon->slug, 'deal_type' => 'rent_daily']) }}"
                   class="px-4 py-1.5 rounded-xl text-xs font-bold transition {{ request('deal_type') === 'rent_daily' ? 'bg-white text-gray-900 shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                    Rent a Car
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
                <h3 class="text-lg font-bold text-gray-800 mt-3">Bu kateqoriyada elan yoxdur</h3>
                <p class="text-sm text-gray-500 mt-1">Avtosalonun digər elanlarına baxa bilərsiniz.</p>
                <a href="{{ route('autosalons.show', $autosalon->slug) }}" class="mt-4 inline-block px-5 py-2.5 bg-[var(--primary)] text-white text-xs font-bold rounded-xl">
                    Bütün Elanları Göstər
                </a>
            </div>
        @endif
    </section>
</div>
@endsection
