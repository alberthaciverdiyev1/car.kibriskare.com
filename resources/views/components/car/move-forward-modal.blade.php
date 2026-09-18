<div id="modal-advance"
     class="fixed inset-0 flex items-center justify-center z-[1000] bg-black/60 backdrop-blur-xs p-4" style="display: none;">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full mx-auto relative p-6 flex flex-col space-y-4 border border-gray-100">
        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-900 gap-2.5 m-0 flex items-center">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
                {{ __('promotion.move_forward') }}
            </h3>
            <button type="button" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 text-lg font-bold flex items-center justify-center transition cursor-pointer" data-close="modal-advance">&times;</button>
        </div>
        
        <p class="text-xs text-gray-600 leading-relaxed">{{ __('promotion.promotion_desc') }}</p>
        
        <h4 class="font-bold text-[11px] text-gray-400 uppercase tracking-wider pt-1">{{ __('promotion.select_duration') }}</h4>
        
        <div class="flex flex-col space-y-2">
            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50/30 cursor-pointer text-sm font-semibold transition group">
                <span class="text-gray-800">1 {{ __('promotion.times') }} / 50 ₺</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-[var(--primary)] focus:ring-[var(--primary)] accent-emerald-600" checked>
            </label>
            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50/30 cursor-pointer text-sm font-semibold transition group">
                <span class="text-gray-800">3 {{ __('promotion.times') }} ({{ __('promotion.every_24_hours') }}) / 120 ₺</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-[var(--primary)] focus:ring-[var(--primary)] accent-emerald-600">
            </label>
            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50/30 cursor-pointer text-sm font-semibold transition group">
                <span class="text-gray-800">5 {{ __('promotion.times') }} ({{ __('promotion.every_24_hours') }}) / 180 ₺</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-[var(--primary)] focus:ring-[var(--primary)] accent-emerald-600">
            </label>
            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-2xl hover:border-emerald-500 hover:bg-emerald-50/30 cursor-pointer text-sm font-semibold transition group">
                <span class="text-gray-800">10 {{ __('promotion.times') }} ({{ __('promotion.every_24_hours') }}) / 300 ₺</span>
                <input type="radio" name="advanceOption" class="h-4 w-4 text-[var(--primary)] focus:ring-[var(--primary)] accent-emerald-600">
            </label>
        </div>

        <div class="border-t border-gray-100 pt-3 flex flex-col space-y-2.5">
            <p class="text-[11px] text-gray-500 leading-tight">{{ __('promotion.payment_terms_notice') }}</p>
            <button type="button" class="w-full bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white font-bold py-3 px-4 rounded-2xl transition cursor-pointer shadow-md transform active:scale-98">
                {{ __('promotion.pay') }}
            </button>
        </div>
    </div>
</div>
