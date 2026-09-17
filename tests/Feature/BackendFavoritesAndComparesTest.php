<?php

namespace Tests\Feature;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\Compare;
use App\Modules\Car\Models\Favorite;
use App\Modules\Shared\Models\User;
use Tests\TestCase;

class BackendFavoritesAndComparesTest extends TestCase
{
    public function test_can_toggle_favorite_via_backend_api_as_guest(): void
    {
        $this->withoutMiddleware();

        $car = Car::first();
        if (! $car) {
            $this->markTestSkipped('No car found');
        }

        // Add
        $response = $this->postJson('/api/favorites/toggle', ['car_id' => $car->id]);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_favorite' => true,
        ]);

        $this->assertDatabaseHas('favorites', [
            'car_id' => $car->id,
        ]);

        // Remove
        $response2 = $this->postJson('/api/favorites/toggle', ['car_id' => $car->id]);
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_favorite' => false,
        ]);
    }

    public function test_can_toggle_compare_via_backend_api_and_enforces_limit(): void
    {
        $this->withoutMiddleware();

        $cars = Car::take(5)->get();
        if ($cars->count() < 5) {
            $this->markTestSkipped('Need at least 5 cars');
        }

        $user = User::first();
        if (! $user) {
            $user = User::create([
                'name' => 'Comp User',
                'email' => 'comp_user_' . time() . '@araba.kibriskare.com',
                'password' => bcrypt('password123'),
            ]);
        }
        Compare::where('user_id', $user->id)->delete();

        // Add 4 cars
        for ($i = 0; $i < 4; $i++) {
            $res = $this->actingAs($user)->postJson('/api/compares/toggle', ['car_id' => $cars[$i]->id]);
            $res->assertStatus(200);
            $res->assertJson(['success' => true, 'is_compared' => true]);
        }

        // 5th should fail with limit error
        $res5 = $this->actingAs($user)->postJson('/api/compares/toggle', ['car_id' => $cars[4]->id]);
        $res5->assertStatus(422);
        $res5->assertJson(['success' => false, 'limit_reached' => true]);
    }

    public function test_compares_page_renders(): void
    {
        $response = $this->get('/compares');
        $response->assertStatus(200);
    }
}

