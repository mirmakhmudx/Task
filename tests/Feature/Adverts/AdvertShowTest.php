<?php

namespace Tests\Feature\Adverts;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_active_advert(): void
    {
        $advert = $this->createAdvert(['status' => Advert::STATUS_ACTIVE]);

        $this->get(route('adverts.show', $advert))
            ->assertOk()
            ->assertSee($advert->title);
    }

    public function test_guest_cannot_view_draft_advert(): void
    {
        $advert = $this->createAdvert(['status' => Advert::STATUS_DRAFT]);

        $this->get(route('adverts.show', $advert))
            ->assertNotFound();
    }

    public function test_guest_cannot_view_moderation_advert(): void
    {
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->get(route('adverts.show', $advert))
            ->assertNotFound();
    }

    public function test_owner_can_view_own_draft_advert(): void
    {
        $user   = User::factory()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_DRAFT, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('adverts.show', $advert))
            ->assertOk();
    }

    public function test_non_owner_cannot_view_draft_advert(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_DRAFT, 'user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('adverts.show', $advert))
            ->assertNotFound();
    }

    public function test_active_advert_shows_price(): void
    {
        $advert = $this->createAdvert([
            'status' => Advert::STATUS_ACTIVE,
            'price'  => 150000,
        ]);

        $this->get(route('adverts.show', $advert))
            ->assertOk()
            ->assertSee('150');
    }

    public function test_active_advert_shows_address(): void
    {
        $advert = $this->createAdvert([
            'status'  => Advert::STATUS_ACTIVE,
            'address' => 'Tashkent city',
        ]);

        $this->get(route('adverts.show', $advert))
            ->assertOk()
            ->assertSee('Tashkent city');
    }

    // ==================== HELPER ====================

    private function createAdvert(array $attrs = []): Advert
    {
        $user     = isset($attrs['user_id'])
            ? User::find($attrs['user_id'])
            : User::factory()->create();
        $category = Category::factory()->create();

        return Advert::create(array_merge([
            'user_id'      => $user->id,
            'category_id'  => $category->id,
            'region_id'    => null,
            'title'        => 'Ommaviy E\'lon',
            'content'      => 'E\'lon kontenti',
            'price'        => 50000,
            'address'      => 'Tashkent',
            'status'       => Advert::STATUS_ACTIVE,
            'published_at' => Carbon::now(),
            'expires_at'   => Carbon::now()->addDays(30),
        ], $attrs));
    }
}
