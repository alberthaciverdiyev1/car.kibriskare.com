/* Qeydiyyat səhifəsi — rol seçici & AJAX qeydiyyat (register.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const i18n = window.registerConfig?.i18n || {};

    const roleTabs = document.querySelectorAll('.role-tab-btn');
    const roleTypeInput = document.getElementById('role_type');
    const roleInfoText = document.getElementById('roleInfoText');
    const labelName = document.getElementById('label_name');

    // Dynamic field wrappers
    const fieldAgencyName = document.getElementById('field_agency_name');
    const inputAgencyName = document.getElementById('agency_name');
    const contactFields = document.getElementById('contactFields');
    const inputPhone = document.getElementById('reg_phone');
    const fieldAgencySelect = document.getElementById('field_agency_select');
    const fieldAgencyAddress = document.getElementById('field_agency_address');
    const registerBtnText = document.getElementById('registerBtnText');

    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('registerSubmitBtn');

    // Role switcher logic
    roleTabs.forEach(btn => {
        btn.addEventListener('click', function () {
            const role = this.dataset.role;
            roleTypeInput.value = role;

            // Update Tab UI
            roleTabs.forEach(b => {
                b.className = 'role-tab-btn py-3 px-3 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 text-gray-600 hover:text-gray-900 cursor-pointer';
            });
            this.className = 'role-tab-btn py-3 px-3 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center gap-2 bg-white text-orange-600 shadow-sm cursor-pointer';

            // Show/Hide Role-specific Fields
            if (role === 'user') {
                fieldAgencyName.classList.add('hidden');
                inputAgencyName.required = false;

                contactFields.classList.add('hidden');
                inputPhone.required = false;

                if (fieldAgencyAddress) fieldAgencyAddress.classList.add('hidden');

                labelName.innerHTML = (i18n.label_user || 'Ad və Soyadınız') + ' <span class="text-rose-500">*</span>';
                roleInfoText.innerHTML = i18n.role_info_user || 'Fərdi istifadəçi olaraq avtomobil elanları yerləşdirə, axtarışları və bəyəndiyiniz avtomobilləri sevimlilər siyahısına əlavə edə bilərsiniz.';
                registerBtnText.textContent = i18n.btn_user || 'İstifadəçi Kimi Qeydiyyatdan Keç';
            } else if (role === 'agency') {
                fieldAgencyName.classList.remove('hidden');
                inputAgencyName.required = true;

                contactFields.classList.remove('hidden');
                inputPhone.required = true;

                if (fieldAgencyAddress) fieldAgencyAddress.classList.remove('hidden');

                labelName.innerHTML = (i18n.label_agency || 'Məsul Şəxsin Ad və Soyadı') + ' <span class="text-rose-500">*</span>';
                roleInfoText.innerHTML = i18n.role_info_agency || '<strong>Avtosalon Hesabı:</strong> Qeydiyyatdan dərhal sonra Avtosalon İdarəetmə Panelinə yönləndiriləcəksiniz, şirkət profilinizi və elanlarınızı idarə edə biləcəksiniz.';
                registerBtnText.textContent = i18n.btn_agency || 'Avtosalon Kimi Qeydiyyatdan Keç';
            }
        });
    });

    // Password Visibility Toggle
    document.querySelectorAll('.toggle-pass-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input) {
                const isPass = input.type === 'password';
                input.type = isPass ? 'text' : 'password';
                icon.className = isPass ? 'bi bi-eye-slash text-base' : 'bi bi-eye text-base';
            }
        });
    });

    // AJAX Form Submission
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });

            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> <span>' + (i18n.checking || 'Qeydiyyat icra edilir...') + '</span>';

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    if (window.KibrisKare && window.KibrisKare.toast) {
                        window.KibrisKare.toast(data.message || i18n.success || 'Qeydiyyat tamamlandı!', 'success');
                    }
                    submitBtn.innerHTML = '<i class="bi bi-check2-circle text-lg"></i> <span>' + (i18n.completed || 'Uğurla tamamlandı!') + '</span>';

                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 800);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;

                    const msg = data.message || i18n.invalid || 'Məlumatları düzgün doldurduğunuzdan əmin olun.';
                    if (window.KibrisKare && window.KibrisKare.toast) {
                        window.KibrisKare.toast(msg, 'error');
                    }

                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errSpan = document.getElementById(key + '_error');
                            if (errSpan) {
                                errSpan.textContent = data.errors[key][0];
                                errSpan.classList.remove('hidden');
                            }
                        });
                    }
                }
            } catch (err) {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                if (window.KibrisKare && window.KibrisKare.toast) {
                    window.KibrisKare.toast(i18n.network || 'Şəbəkə xətası baş verdi. Yenidən cəhd edin.', 'error');
                }
            }
        });
    }
});
