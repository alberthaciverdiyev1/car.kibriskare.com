<?php

namespace App\Modules\Car\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Autosalon;
use App\Modules\Car\Models\Car;
use App\Modules\Location\Models\City;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutosalonController extends Controller
{
    public function index(Request $request, ...$params): View
    {
        $query = Autosalon::query()
            ->with(['city'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }

        $autosalons = $query->orderByDesc('is_verified')
            ->orderByDesc('cars_count')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $cities = City::where('is_active', true)->orderBy('name->tr')->get();

        $breadcrumbs = [
            ['title' => __('navbar.home'), 'url' => route('home')],
            ['title' => __('navbar.autosalons'), 'url' => ''],
        ];

        return view('pages.autosalon.list', compact('autosalons', 'cities', 'breadcrumbs'));
    }

    public function show(Request $request, ...$params): View
    {
        $slug = end($params);

        $autosalon = Autosalon::where('slug', $slug)
            ->where('is_active', true)
            ->with(['city', 'district'])
            ->firstOrFail();

        $carsQuery = Car::query()
            ->where('autosalon_id', $autosalon->id)
            ->where('status', CarStatus::Active->value)
            ->with(['brand', 'model', 'city', 'images', 'autosalon']);

        if ($request->filled('deal_type')) {
            $carsQuery->where('deal_type', $request->input('deal_type'));
        }

        $cars = $carsQuery->latest('published_at')->paginate(12)->withQueryString();

        $breadcrumbs = [
            ['title' => __('navbar.home'), 'url' => route('home')],
            ['title' => __('navbar.autosalons'), 'url' => route('autosalons.index')],
            ['title' => $autosalon->name, 'url' => ''],
        ];

        return view('pages.autosalon.show', compact('autosalon', 'cars', 'breadcrumbs'));
    }
}
