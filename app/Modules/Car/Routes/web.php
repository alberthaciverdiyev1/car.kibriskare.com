<?php

use App\Modules\Car\Controllers\AddCarController;
use App\Modules\Car\Controllers\AutosalonController;
use App\Modules\Car\Controllers\CarDetailController;
use App\Modules\Car\Controllers\CarHomeController;
use Illuminate\Support\Facades\Route;

// Ana Sayfa & Avtomobil İlan Listesi
Route::get('/', CarHomeController::class)->name('home');
Route::get('/ilanlar', CarHomeController::class)->name('listing');
Route::get('/ilanlar/{first}', CarHomeController::class)->name('listing.path1');
Route::get('/ilanlar/{first}/{second}', CarHomeController::class)->name('listing.path2');
Route::get('/ilanlar/{first}/{second}/{third}', CarHomeController::class)->name('listing.path3');
Route::get('/arabalar', CarHomeController::class)->name('cars.index');
Route::get('/cars', CarHomeController::class);
Route::get('/avtomobiller', CarHomeController::class);

// Avtosalonlar Kataloqu & Detay
Route::get('/avtosalonlar', [AutosalonController::class, 'index'])->name('autosalons.index');
Route::get('/autosalons', [AutosalonController::class, 'index']);
Route::get('/agencies', [AutosalonController::class, 'index'])->name('agencies.list');
Route::get('/avtosalon/{slug}', [AutosalonController::class, 'show'])->name('autosalons.show');
Route::get('/autosalon/{slug}', [AutosalonController::class, 'show']);
Route::get('/agency/{slug}', [AutosalonController::class, 'show'])->name('agencies.detail');

// Avtomobil Detay Sayfası
Route::get('/araba/{slug}', CarDetailController::class)->name('cars.show');
Route::get('/car/{slug}', CarDetailController::class);
Route::get('/ilan/{slug}', CarDetailController::class)->name('properties.show');
Route::get('/elan/{slug}', CarDetailController::class);

// İlan Ekle (Add Car)
Route::get('/ilan-ver', [AddCarController::class, 'create'])->name('add-car');
Route::get('/add-car', [AddCarController::class, 'create']);
Route::get('/add-property', [AddCarController::class, 'create'])->name('add-property');
Route::get('/elan-yerlesdir', [AddCarController::class, 'create']);
Route::post('/ilan-ver', [AddCarController::class, 'store'])->name('add-car.store');
Route::post('/add-car', [AddCarController::class, 'store']);
Route::post('/add-property', [AddCarController::class, 'store'])->name('add-property.store');

// AJAX Models by Brand
Route::get('/api/brands/{brandId}/models', [CarHomeController::class, 'modelsByBrand'])->name('api.brands.models');
