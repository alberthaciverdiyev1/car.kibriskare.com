@if($cars->hasPages())
    <nav class="flex items-center justify-center gap-1 sm:gap-2 mt-10" aria-label="Pagination">
        {{-- Previous Page Link --}}
        @if($cars->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                <i class="bi bi-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $cars->previousPageUrl() }}" data-page="{{ $cars->currentPage() - 1 }}"
               class="pagination-link w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-orange-50 hover:text-[var(--primary)] hover:border-[var(--primary)] transition shadow-2xs font-semibold text-sm">
                <i class="bi bi-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach($cars->getUrlRange(max(1, $cars->currentPage() - 2), min($cars->lastPage(), $cars->currentPage() + 2)) as $page => $url)
            @if($page == $cars->currentPage())
                <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-[var(--primary)] text-white font-bold text-sm shadow-sm">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}" data-page="{{ $page }}"
                   class="pagination-link w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-orange-50 hover:text-[var(--primary)] hover:border-[var(--primary)] transition shadow-2xs font-semibold text-sm">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if($cars->hasMorePages())
            <a href="{{ $cars->nextPageUrl() }}" data-page="{{ $cars->currentPage() + 1 }}"
               class="pagination-link w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-orange-50 hover:text-[var(--primary)] hover:border-[var(--primary)] transition shadow-2xs font-semibold text-sm">
                <i class="bi bi-chevron-right text-xs"></i>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                <i class="bi bi-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif
