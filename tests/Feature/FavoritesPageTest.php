<?php

namespace Tests\Feature;

use App\Modules\Car\Enums\CarStatus;
use App\Modules\Car\Models\Car;
use Tests\TestCase;

class FavoritesPageTest extends TestCase
{
    public function test_favorites_page_renders_successfully(): void
    {
        $response = $this->get(route('favorites'));

        $response->assertStatus(200);
        $response->assertSee('Seçilmiş Elanlar');
        $response->assertSee('id="favoritesContainer"', false);
    }

    public function test_favorites_items_endpoint_returns_rendered_cards(): void
    {
        $this->withoutMiddleware();

        $car = Car::where('status', CarStatus::Active)->first();
        if (! $car) {
            $this->markTestSkipped('No active car found.');
        }

        $response = $this->postJson(route('favorites.items'), [
            'ids' => [$car->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);
        $data = $response->json();
        $this->assertStringContainsString('data-fav-id="' . $property->id . '"', $data['html']);
    }

    public function test_favorites_items_endpoint_handles_empty_ids(): void
    {
        $this->withoutMiddleware();

        $response = $this->postJson(route('favorites.items'), [
            'ids' => [],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 0,
            'html' => '',
        ]);
    }
}
