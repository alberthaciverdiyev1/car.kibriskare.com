@props(['car'])

<div id="report-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs hidden">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">{{ __('Hatalı / Şüpheli İlanı Bildir') }}</h3>
            </div>
            <button type="button" onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="report-form" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Bildirim Nedeni') }} *</label>
                <select name="reason" required class="w-full text-sm px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                    <option value="">{{ __('Lütfen bir neden seçin') }}</option>
                    <option value="fake_ad">{{ __('Sahte / Gerçek Olmayan İlan') }}</option>
                    <option value="wrong_info">{{ __('Hatalı Bilgi (Fiyat, Km, Boya-Hasar)') }}</option>
                    <option value="already_sold">{{ __('Araç Satılmış / İlan Güncel Değil') }}</option>
                    <option value="scam_deposit">{{ __('Şüpheli Satıcı / Kapora Dolandırıcılığı') }}</option>
                    <option value="inappropriate">{{ __('Uygunsuz İçerik veya Fotoğraf') }}</option>
                    <option value="other">{{ __('Diğer Nedenler') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('Açıklama / Detay') }}</label>
                <textarea name="description" rows="3" placeholder="{{ __('Gördüğünüz hatayı veya şüphenizi kısaca açıklayınız...') }}"
                          class="w-full text-sm px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">{{ __('İletişim Numaranız veya E-posta (İsteğe bağlı)') }}</label>
                <input type="text" name="contact_info" placeholder="{{ __('Örn: 0533 123 4567') }}"
                       class="w-full text-sm px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>

            <div id="report-message" class="hidden p-3 rounded-xl text-xs font-medium"></div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    {{ __('İptal') }}
                </button>
                <button type="submit" id="report-submit-btn" class="px-5 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition-colors">
                    {{ __('Bildirimi Gönder') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal() {
        const modal = document.getElementById('report-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeReportModal() {
        const modal = document.getElementById('report-modal');
        if (modal) modal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('report-form');
        const msg = document.getElementById('report-message');
        const submitBtn = document.getElementById('report-submit-btn');

        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                submitBtn.disabled = true;
                submitBtn.innerText = '{{ __('Göndərilir...') }}';

                try {
                    const formData = new FormData(form);
                    const response = await fetch('{{ route('cars.report') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    msg.classList.remove('hidden', 'bg-rose-50', 'text-rose-700', 'bg-emerald-50', 'text-emerald-700');
                    if (data.success) {
                        msg.classList.add('bg-emerald-50', 'text-emerald-700');
                        msg.innerText = data.message;
                        form.reset();
                        setTimeout(() => {
                            closeReportModal();
                            msg.classList.add('hidden');
                            submitBtn.disabled = false;
                            submitBtn.innerText = '{{ __('Bildirimi Gönder') }}';
                        }, 2000);
                    } else {
                        msg.classList.add('bg-rose-50', 'text-rose-700');
                        msg.innerText = data.message || '{{ __('Xəta baş verdi') }}';
                        submitBtn.disabled = false;
                        submitBtn.innerText = '{{ __('Bildirimi Gönder') }}';
                    }
                } catch (err) {
                    msg.classList.remove('hidden');
                    msg.classList.add('bg-rose-50', 'text-rose-700');
                    msg.innerText = '{{ __('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.') }}';
                    submitBtn.disabled = false;
                    submitBtn.innerText = '{{ __('Bildirimi Gönder') }}';
                }
            });
        }
    });
</script>
