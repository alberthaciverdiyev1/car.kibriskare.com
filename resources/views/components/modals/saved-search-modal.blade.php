<!-- Saved Searches Modal -->
<div id="savedSearchModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl flex flex-col overflow-hidden border border-gray-200" id="savedSearchModalCard">
        
        <!-- Header -->
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="bi bi-bookmark-fill"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base leading-tight">{{ __('Yadda Saxlanılmış Axtarışlar') }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ __('Axtarış parametrlərinizi qeyd edin və ya əvvəlkiləri idarə edin') }}</p>
                </div>
            </div>
            <button type="button" id="closeSavedSearchModalBtn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer" title="{{ __('Kapat') }}">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-5 space-y-5 overflow-y-auto max-h-[75vh]">
            
            @auth
                <!-- Save Current Search Form -->
                <div class="bg-blue-50/60 p-4 rounded-xl border border-blue-100 space-y-3">
                    <div class="text-xs font-bold text-blue-950 flex items-center gap-1.5">
                        <i class="bi bi-plus-circle-fill text-blue-600"></i>
                        <span>{{ __('Cari Axtarışı Yadda Saxla') }}</span>
                    </div>

                    <div class="space-y-2">
                        <input type="text" id="savedSearchTitleInput"
                               placeholder="{{ __('Məs: Girnədə Ucuz Otomobillər') }}"
                               class="w-full h-10 px-3 bg-white border border-blue-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-blue-500">

                        <button type="button" id="btnSubmitSaveSearch"
                                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                            <i class="bi bi-check-lg"></i>
                            <span>{{ __('Bu Axtarışı Qeyd Et') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Existing Saved Searches -->
                <div class="space-y-2.5">
                    <div class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center justify-between">
                        <span>{{ __('Əvvəlki Axtarışlarınız') }}</span>
                        <span id="savedSearchesCountBadge" class="text-[11px] font-semibold text-gray-400">0 axtarış</span>
                    </div>

                    <div id="savedSearchesList" class="space-y-2 min-h-[80px]">
                        <div class="text-xs text-gray-400 py-4 text-center">
                            {{ __('Yüklənir...') }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest notice -->
                <div class="text-center py-6 space-y-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto text-xl">
                        <i class="bi bi-person-lock"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">{{ __('Giriş Tələb Olunur') }}</h4>
                        <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">
                            {{ __('Axtarışlarınızı yadda saxlamaq və uyğun elanlar daxil olduqda xəbərdar olmaq üçün zəhmət olmasa daxil olun.') }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs">
                            <i class="bi bi-box-arrow-in-right"></i>
                            {{ __('Daxil Ol / Qeydiyyat') }}
                        </a>
                    </div>
                </div>
            @endauth

        </div>

        <!-- Footer -->
        <div class="px-5 py-3 border-t border-gray-200 flex justify-end bg-gray-50 shrink-0">
            <button type="button" id="closeSavedSearchFooterBtn" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg cursor-pointer hover:bg-gray-50">
                {{ __('Bağla') }}
            </button>
        </div>

    </div>
</div>
