<?php

namespace Tests\Feature;

use App\Modules\Car\Models\Car;
use App\Modules\Shared\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentPagesSmokeTest extends TestCase
{
    public function test_admin_profile_page_renders(): void
    {
        $user = User::where('email', User::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => User::ADMIN_EMAIL,
                'password' => bcrypt('password'),
            ]);
        }

        $this->actingAs($user);
        Livewire::test(\App\Filament\Pages\EditProfile::class)
            ->assertOk()
            ->assertSee('Profilim');
    }

    public function test_admin_car_list_page_renders(): void
    {
        $user = User::where('email', User::ADMIN_EMAIL)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => User::ADMIN_EMAIL,
                'password' => bcrypt('password'),
            ]);
        }

        $this->actingAs($user);
        Livewire::test(\App\Filament\Admin\Resources\CarResource\Pages\ListCars::class)
            ->assertOk();
    }

    public function test_agency_car_list_page_renders(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'dealer@araba.kibriskare.com'],
            ['name' => 'Dealer User', 'password' => bcrypt('password')]
        );

        $this->actingAs($user);
        Livewire::test(\App\Filament\Agency\Resources\CarResource\Pages\ListCars::class)
            ->assertOk();
    }
}

