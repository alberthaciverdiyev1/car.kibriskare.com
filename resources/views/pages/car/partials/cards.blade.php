@if(count($cars) > 0)
    <div id="carsGridContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
        @foreach($cars as $car)
            <x-car-card :car="$car" />
        @endforeach
    </div>
@else
    <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-gray-100 p-8 shadow-xs">
        <div class="w-16 h-16 bg-orange-50 text-[var(--primary)] rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            <i class="bi bi-car-front"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Axtarışınıza uyğun avtomobil tapılmadı</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Filtrləri sıfırlayaraq və ya parametrləri genişləndirərək yenidən axtarmağı yoxlayın.</p>
        <button type="button" onclick="document.getElementById('resetFiltersBtn')?.click()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-xl text-sm font-semibold transition">
            <i class="bi bi-arrow-clockwise"></i> Filtrləri Sıfırla
        </button>
    </div>
@endif
