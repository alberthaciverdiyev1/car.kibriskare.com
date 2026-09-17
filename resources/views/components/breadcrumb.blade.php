@php
    $navItems = $items ?? $breadcrumbs ?? [];
@endphp

@if(!empty($navItems))
<nav aria-label="Breadcrumb" class="py-2">
    <ol class="inline-flex items-center flex-wrap gap-1 sm:gap-1.5 bg-white border border-gray-200/90 rounded-2xl py-1.5 px-3 sm:px-4 shadow-2xs">
        @foreach($navItems as $item)
            @php
                $itemLabel = $item['label'] ?? $item['title'] ?? '';
            @endphp
            <li class="inline-flex items-center gap-1 sm:gap-1.5">
                @if(!$loop->first)
                    <span class="text-gray-300 text-xs font-light select-none px-0.5" aria-hidden="true">/</span>
                @endif

                @if(!empty($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}"
                       class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-medium text-gray-600 hover:text-[var(--primary)] transition-colors duration-150 py-1 px-2 rounded-xl hover:bg-emerald-50/50">
                        @if($loop->first)
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 text-[var(--primary)] group-hover:bg-[var(--primary)] group-hover:text-white flex items-center justify-center transition-colors duration-150">
                                <i class="fa-solid fa-house text-[11px]"></i>
                            </span>
                        @endif
                        <span>{{ $itemLabel }}</span>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-gray-900 py-1 px-2.5 rounded-xl bg-gray-50 border border-gray-200/80 max-w-[200px] sm:max-w-[380px] md:max-w-none truncate"
                          aria-current="page">
                        @if($loop->first)
                            <span class="w-6 h-6 rounded-lg bg-[var(--primary)] text-white flex items-center justify-center">
                                <i class="fa-solid fa-house text-[11px]"></i>
                            </span>
                        @endif
                        <span class="truncate">{{ $itemLabel }}</span>
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
