@extends('layouts.app')

@section('title', __('compare.page_title') . ' - araba.kibriskare.com')

@section('content')
    <div class="max-w-[1400px] mx-auto sm:px-6 lg:px-8 py-8">

        @if(isset($breadcrumbs))
            <div class="mb-6">
                @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
            </div>
        @endif

        @php
            $carItems = isset($cars) ? $cars : (isset($properties) ? $properties : collect());
        @endphp

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 shadow-sm">
                    <i class="bi bi-arrow-left-right text-2xl font-semibold"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ __('compare.page_title') }}</h1>
                        <span id="compareTotalBadge"
                              class="{{ count($carItems) > 0 ? '' : 'hidden' }} px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-600">
                        {{ count($carItems) }} / 4
                    </span>
                    </div>
                </div>
            </div>

            <div id="compareActions" class="{{ count($carItems) > 0 ? '' : 'hidden' }} flex items-center gap-3">
                <button id="clearAllCompareBtn" type="button"
                        data-confirm="{{ __('compare.confirm_clear_all') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl transition duration-200 border border-rose-200 cursor-pointer">
                    <i class="fa-regular fa-trash-can text-sm"></i>
                    <span>{{ __('compare.clear_all') }}</span>
                </button>
                @if(count($carItems) < 4)
                    <a href="{{ route('listing') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition duration-200">
                        <i class="bi bi-plus-circle"></i>
                        <span>{{ __('compare.add_more_properties') }}</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Empty State -->
        <div id="compareEmptyState"
             class="{{ count($carItems) === 0 ? '' : 'hidden' }} text-center py-16 px-4 bg-white rounded-3xl border border-gray-100 shadow-sm max-w-lg mx-auto">
            <div class="w-20 h-20 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ __('compare.empty_title') }}</h3>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto leading-relaxed">
                {{ __('compare.empty_desc') }}
            </p>
            <a href="{{ route('listing') }}"
               class="inline-flex items-center px-6 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl shadow-md transition-all duration-200 hover:shadow-lg">
                <i class="bi bi-search mr-2"></i>
                <span>{{ __('compare.explore_listings') }}</span>
            </a>
        </div>

        <!-- Compare Table Container -->
        @if(count($carItems) > 0)
            <div id="compareTableContainer"
                 class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-x-auto w-full">
                <table class="w-full text-left border-collapse table-auto min-w-[650px]">
                    <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200">
                        <th class="p-3 sm:p-4 w-32 sm:w-44 text-xs font-semibold uppercase tracking-wider text-gray-400 align-top">
                            {{ __('compare.parameter') }}
                        </th>
                        @foreach($carItems as $car)
                            <th class="p-3 sm:p-4 text-center border-l border-gray-100 relative group align-top"
                                data-comp-header-id="{{ $car->id }}">
                                <button type="button" onclick="removeCompareItem({{ $car->id }})"
                                        class="absolute top-2 right-2 w-7 h-7 bg-white hover:bg-rose-50 text-gray-400 hover:text-rose-600 rounded-full flex items-center justify-center shadow-sm border border-gray-200 transition cursor-pointer z-10"
                                        title="{{ __('compare.remove_from_compare') }}">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                                <div class="w-full max-w-[220px] h-[130px] sm:h-[150px] mx-auto rounded-xl overflow-hidden mb-2.5 bg-gray-100 shadow-sm relative">
                                    <img src="{{ $car->images->first()?->thumb ?: asset('images/box-house.jpg') }}" alt="{{ $car->display_title }}"
                                         class="w-full h-full object-cover"/>
                                </div>
                                <a href="{{ route('cars.show', $car->slug) }}"
                                   class="font-semibold text-xs sm:text-sm text-gray-900 hover:text-orange-500 line-clamp-2 transition mb-1.5 block max-w-[240px] mx-auto min-h-[34px]">
                                    {{ $car->display_title }}
                                </a>
                                <div class="text-sm sm:text-base font-bold text-orange-600">
                                    {{ $car->formatted_price }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                    @php
                        $getLocalizedName = function ($model) {
                            if (! $model) return '—';
                            $name = $model->name;
                            if (is_array($name)) {
                                return $name[app()->getLocale()] ?? $name['az'] ?? reset($name) ?? '—';
                            }
                            return (string) ($name ?: '—');
                        };
                    @endphp

                    <!-- Marka / Model -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.brand_model') ?? 'Marka / Model' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 font-medium text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->brand?->name ?? '—' }} {{ $car->model?->name ? ' / ' . $car->model->name : '' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Buraxılış İli -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.year') ?? 'Buraxılış İli' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->year ?: '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Yürüş -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.mileage') ?? 'Yürüş' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 font-medium text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->formatted_mileage ?: '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Ban Növü -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.body_type') ?? 'Ban Növü' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->bodyType?->localized_name ?: '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Yanacaq Növü -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.fuel_type') ?? 'Yanacaq Növü' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->fuel_type ? $car->fuel_type->label() : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Sürətlər Qutusu -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.transmission') ?? 'Sürətlər Qutusu' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->transmission ? $car->transmission->label() : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Mühərrik Həcmi / Gücü -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.engine') ?? 'Mühərrik' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->engine_volume_l ?: ($car->engine_volume ? $car->engine_volume . ' cc' : '') }}
                                @if($car->engine_power)
                                    ({{ $car->engine_power }} a.g.)
                                @endif
                                @if(!$car->engine_volume && !$car->engine_power)
                                    —
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Ötürücü -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.drivetrain') ?? 'Ötürücü' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->drivetrain ? $car->drivetrain->label() : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Sükan -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.steering_wheel') ?? 'Sükan' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->steering_wheel ? $car->steering_wheel->label() : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Vəziyyəti -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.condition') ?? 'Vəziyyəti' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $car->condition ? $car->condition->label() : '—' }}
                            </td>
                        @endforeach
                    </tr>

                    <!-- Şəhər -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.location_city') }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-gray-800"
                                data-comp-col-id="{{ $car->id }}">
                                {{ $getLocalizedName($car->city) }} @if($car->district)
                                    ({{ $getLocalizedName($car->district) }})
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Gömrük / Plaka -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.customs_cleared') ?? 'Gömrük / Plaka' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100"
                                data-comp-col-id="{{ $car->id }}">
                                @if($car->is_customs_cleared)
                                    <span class="inline-flex items-center text-emerald-600 font-semibold gap-1">
                                    <i class="bi bi-check-circle-fill"></i> {{ __('compare.kktc_plate') ?? 'KKTC Plakalı' }}
                                </span>
                                @else
                                    <span class="inline-flex items-center text-amber-600 font-semibold gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ __('compare.not_customs_cleared') ?? 'Gömrük olunmayıb' }}
                                </span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- Kredit / Barter -->
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-3 sm:p-4 font-semibold text-gray-700 bg-gray-50/30">{{ __('compare.credit_barter') ?? 'Kredit / Barter' }}</td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100 text-xs"
                                data-comp-col-id="{{ $car->id }}">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="{{ $car->is_credit_available ? 'text-emerald-600 font-semibold' : 'text-gray-400' }}">
                                        {{ __('compare.credit') ?? 'Kredit' }}: {{ $car->is_credit_available ? __('compare.yes') : __('compare.no') }}
                                    </span>
                                    <span class="{{ $car->is_barter_available ? 'text-emerald-600 font-semibold' : 'text-gray-400' }}">
                                        {{ __('compare.barter') ?? 'Barter' }}: {{ $car->is_barter_available ? __('compare.yes') : __('compare.no') }}
                                    </span>
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    <!-- View button -->
                    <tr class="bg-gray-50/50">
                        <td class="p-3 sm:p-4 bg-gray-50/80"></td>
                        @foreach($carItems as $car)
                            <td class="p-3 sm:p-4 text-center border-l border-gray-100"
                                data-comp-col-id="{{ $car->id }}">
                                <a href="{{ route('cars.show', $car->slug) }}"
                                   class="inline-flex items-center justify-center max-w-[200px] w-full py-2 sm:py-2.5 px-3 sm:px-4 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                                    {{ __('compare.view_listing') }} <i class="bi bi-arrow-right ml-1.5"></i>
                                </a>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

    </div>

    @push('scripts')
    <script src="{{ asset('js/pages/compare/compare.js') }}"></script>
@endpush
@endsection
