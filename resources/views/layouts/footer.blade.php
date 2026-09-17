@php
    $siteSetting = $siteSetting ?? \App\Modules\Shared\Models\SiteSetting::current();
@endphp
<footer class="bg-neutral-950 text-neutral-400 mt-20 border-t border-neutral-900">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
                    <img class="h-9 w-auto object-contain" width="36" height="36" src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare" />
                    <div class="leading-tight">
                        <div class="text-xl font-bold text-white tracking-tight">{{ $siteSetting?->copyright_text ?: 'KibrisKare.com' }}</div>
                        <div class="text-[8px] text-emerald-400 underline underline-offset-4 uppercase tracking-[0.15em] font-semibold">
                            {{ $siteSetting?->getTrans('tagline') ?: __('footer.tagline') }}
                        </div>
                    </div>
                </a>
                <p class="text-sm text-neutral-400 leading-relaxed max-w-sm">
                    {{ $siteSetting?->getTrans('footer_description') ?: 'Kuzey Kıbrıs genelinde satılık ve günlük kiralık (rent a car) araç ilanları, güncel avtosalon kataloğu ve oto pazarı.' }}
                </p>
                <div class="flex items-center space-x-4 pt-2">
                    @if($siteSetting?->facebook_url)
                        <a href="{{ $siteSetting->facebook_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-facebook text-lg"></i>
                        </a>
                    @endif
                    @if($siteSetting?->instagram_url)
                        <a href="{{ $siteSetting->instagram_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-instagram text-lg"></i>
                        </a>
                    @endif
                    @if($siteSetting?->linkedin_url)
                        <a href="{{ $siteSetting->linkedin_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-linkedin text-lg"></i>
                        </a>
                    @endif
                    @if($siteSetting?->youtube_url)
                        <a href="{{ $siteSetting->youtube_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-youtube text-lg"></i>
                        </a>
                    @endif
                    @if($siteSetting?->telegram_url)
                        <a href="{{ $siteSetting->telegram_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-telegram text-lg"></i>
                        </a>
                    @endif
                    @if($siteSetting?->tiktok_url)
                        <a href="{{ $siteSetting->tiktok_url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-neutral-900 text-neutral-400 hover:text-white hover:bg-[var(--primary)] flex items-center justify-center border border-neutral-800 transition duration-300 shadow-sm">
                            <i class="bi bi-tiktok text-lg"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Navigation -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.quick_links') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white hover:underline transition">{{ __('footer.home') }}</a></li>
                    <li><a href="{{ route('listing', ['deal_type' => 'sale']) }}" class="hover:text-white hover:underline transition">{{ __('navbar.sale') }}</a></li>
                    <li><a href="{{ route('listing', ['deal_type' => 'rent_daily']) }}" class="hover:text-white hover:underline transition">{{ __('navbar.rent') }}</a></li>
                    <li><a href="{{ route('autosalons.index') }}" class="hover:text-white hover:underline transition">{{ __('navbar.autosalons') }}</a></li>
                    <li><a href="{{ route('about-us') }}" class="hover:text-white hover:underline transition">{{ __('footer.about_us') }}</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white hover:underline transition">{{ __('footer.faq') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white hover:underline transition">{{ __('footer.contact') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Locations -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.popular_regions') }}</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('listing', ['city' => 'girne']) }}" class="hover:text-white hover:underline transition">{{ __('footer.girne') }}</a></li>
                    <li><a href="{{ route('listing', ['city' => 'lefkosa']) }}" class="hover:text-white hover:underline transition">{{ __('footer.lefkosa') }}</a></li>
                    <li><a href="{{ route('listing', ['city' => 'gazimagusa']) }}" class="hover:text-white hover:underline transition">{{ __('footer.gazimagusa') }}</a></li>
                    <li><a href="{{ route('listing', ['city' => 'iskele']) }}" class="hover:text-white hover:underline transition">{{ __('footer.iskele') }}</a></li>
                    <li><a href="{{ route('listing', ['city' => 'guzelyurt']) }}" class="hover:text-white hover:underline transition">{{ __('footer.guzelyurt') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div class="space-y-4">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider">{{ __('footer.contact_support') }}</h4>
                <ul class="space-y-3.5 text-sm">
                    @if($siteSetting?->phone)
                        <li class="flex items-start space-x-3">
                            <i class="bi bi-telephone text-emerald-400 mt-0.5 text-base"></i>
                            <a href="tel:{{ preg_replace('/[^\d\+]/', '', $siteSetting->phone) }}" class="text-neutral-300 font-semibold hover:text-white transition">{{ $siteSetting->phone }}</a>
                        </li>
                    @endif
                    @if($siteSetting?->email)
                        <li class="flex items-start space-x-3">
                            <i class="bi bi-envelope text-emerald-400 mt-0.5 text-base"></i>
                            <a href="mailto:{{ $siteSetting->email }}" class="hover:text-white transition">{{ $siteSetting->email }}</a>
                        </li>
                    @endif
                    <li class="flex items-start space-x-3">
                        <i class="bi bi-geo-alt text-emerald-400 mt-0.5 text-base"></i>
                        <span>{{ $siteSetting?->getTrans('address') ?: __('footer.location_address') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-neutral-900 my-12">

        <!-- Bottom Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                &copy; {{ date('Y') }} <span class="text-white font-semibold">{{ $siteSetting?->copyright_text ?: 'KibrisKare.com' }}</span> — {{ __('footer.all_rights_reserved') }}
            </div>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                <a href="{{ route('user-agreement') }}" class="hover:text-white transition">{{ __('footer.user_agreement') }}</a>
                <a href="{{ route('privacy-policy') }}" class="hover:text-white transition">{{ __('footer.privacy_policy') }}</a>
                <a href="{{ route('terms-of-use') }}" class="hover:text-white transition">{{ __('footer.terms_of_use') }}</a>
            </div>
        </div>
    </div>
</footer>
