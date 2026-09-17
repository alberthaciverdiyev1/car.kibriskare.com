
<div id="mobileMoreDrawer" class="hidden md:hidden fixed inset-0 z-50">
  <div class="w-full h-full flex flex-col justify-end bg-black/60 backdrop-blur-xs transition-opacity duration-300">
  <div class="flex-1" id="mobileMoreDrawerBackdrop"></div>

  <div class="bg-white rounded-t-3xl max-h-[95vh] overflow-y-auto shadow-2xl p-5 space-y-4 border-t border-gray-100 transform transition-transform duration-300">

    <!-- Drawer Header -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
      <div class="flex items-center space-x-2">
        <img class="h-8 w-auto object-contain" src="{{ asset('images/kibriskarelogo1.png') }}" alt="KibrisKare.com" />
        <span class="font-bold text-base text-gray-800">KibrisKare.com</span>
      </div>
      <button type="button" id="closeMobileMoreDrawer" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition">
        <i class="bi bi-x-lg text-sm"></i>
      </button>
    </div>

    <!-- Quick Action Buttons -->
    <div class="grid grid-cols-1 gap-2.5">
      <a href="{{ route('add-car') }}" class="flex items-center justify-center gap-2 py-3 px-4 bg-[var(--primary)] hover:bg-[var(--primary-hover)] text-white rounded-2xl font-bold text-sm shadow-sm active:scale-95 transition-all">
        <i class="bi bi-plus-circle text-base"></i>
        <span>{{ __('navbar.post_car') }}</span>
      </a>
    </div>

    <!-- Navigation Links List -->
    <div class="space-y-1 py-1">
      <a href="{{ route('home') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('/') ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-house text-gray-400 w-5 text-center"></i> {{ __('navbar.home') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('listing', ['deal_type' => 'sale']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request('deal_type') === 'sale' ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-car text-gray-400 w-5 text-center"></i> {{ __('navbar.sale') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('listing', ['deal_type' => 'rent_daily']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request('deal_type') === 'rent_daily' ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-key text-gray-400 w-5 text-center"></i> {{ __('navbar.rent') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('autosalons.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('*avtosalon*') || request()->is('*autosalon*') ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-building text-gray-400 w-5 text-center"></i> {{ __('navbar.autosalons') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('contact') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('*iletisim*') || request()->is('*contact*') ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-gray-400 w-5 text-center"></i> {{ __('navbar.contact') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('favorites') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('favorites*') ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="fa-regular fa-heart text-gray-400 w-5 text-center"></i> {{ __('navbar.favorites') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
      <a href="{{ route('compares') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl {{ request()->is('compares*') ? 'text-[var(--primary)] bg-emerald-50 font-semibold' : 'text-gray-700 hover:bg-gray-50 font-medium' }} text-sm">
        <span class="flex items-center gap-3"><i class="bi bi-arrow-left-right text-gray-400 w-5 text-center"></i> {{ __('navbar.compare') }}</span>
        <i class="bi bi-chevron-right text-xs text-gray-300"></i>
      </a>
    </div>

    <!-- Language Selector with Flags -->
    <div class="pt-3 border-t border-gray-100">
      <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('navbar.select_language') }}</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach($languages as $lKey => $lData)
          <a href="{{ route('lang.switch', ['lang' => $lKey]) }}"
             class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-2xl text-xs font-semibold border {{ $currentLocale === $lKey ? 'border-[var(--primary)] bg-emerald-50 text-[var(--primary)] shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span class="text-sm">{{ $lData['flag'] }}</span>
            <span>{{ $lData['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Currency Selector -->
    <div class="pt-3 border-t border-gray-100">
      <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('navbar.currency') }}</div>
      <div class="grid grid-cols-4 gap-1.5">
        @foreach($currencySymbols as $cCode => $cSym)
          <a href="{{ route('currency.switch', ['code' => $cCode]) }}"
             class="flex items-center justify-center gap-1.5 py-2 px-2 rounded-2xl text-xs font-semibold border {{ $currentCurrency === $cCode ? 'border-[var(--primary)] bg-emerald-50 text-[var(--primary)] shadow-2xs' : 'border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span>{{ $cSym }}</span>
            <span>{{ $cCode }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Auth / Logout -->
    @auth
      <div class="pt-3 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}" class="m-0 js-logout">
          @csrf
          <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-red-50 text-red-600 rounded-2xl font-bold text-xs hover:bg-red-100 transition">
            <i class="bi bi-box-arrow-right"></i>
            <span>{{ __('navbar.logout') }}</span>
          </button>
        </form>
      </div>
    @endauth

  </div>
  </div>
</div>
