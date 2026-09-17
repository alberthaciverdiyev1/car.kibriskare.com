@forelse($cars as $car)
    <div class="favorite-card-wrapper transition-all duration-300" data-fav-id="{{ $car->id }}">
        <x-car-card :car="$car" />
    </div>
@empty
    <div class="col-span-full text-center py-12">
        <p class="text-gray-500 font-medium">{{ __('favorites.empty_message') }}</p>
    </div>
@endforelse
