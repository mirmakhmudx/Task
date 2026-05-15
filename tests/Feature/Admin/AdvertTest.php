<?php

namespace Tests\Feature\Admin;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertTest extends TestCase
{
    use RefreshDatabase;

    // ==================== MODERATE ====================

    public function test_admin_can_moderate_advert(): void
    {
        $admin  = User::factory()->admin()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($admin)
            ->post(route('admin.adverts.moderate', $advert))
            ->assertRedirect();

        $this->assertTrue($advert->fresh()->isActive());
    }

    public function test_regular_user_cannot_moderate(): void
    {
        $user   = User::factory()->create(['role' => User::ROLE_USER]);
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($user)
            ->post(route('admin.adverts.moderate', $advert))
            ->assertForbidden();
    }

    public function test_guest_cannot_moderate(): void
    {
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->post(route('admin.adverts.moderate', $advert))
            ->assertRedirect(route('login'));
    }

    public function test_cannot_moderate_draft_advert(): void
    {
        $admin  = User::factory()->admin()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_DRAFT]);

        $this->actingAs($admin)
            ->post(route('admin.adverts.moderate', $advert))
            ->assertSessionHasErrors();
    }

    // ==================== REJECT ====================

    public function test_admin_can_reject_advert_with_reason(): void
    {
        $admin  = User::factory()->admin()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($admin)
            ->post(route('admin.adverts.reject', $advert), [
                'reason' => 'Qoidalarga zid kontent.',
            ])
            ->assertRedirect();

        $fresh = $advert->fresh();
        $this->assertTrue($fresh->isDraft());
        $this->assertEquals('Qoidalarga zid kontent.', $fresh->reject_reason);
    }

    public function test_reject_requires_reason(): void
    {
        $admin  = User::factory()->admin()->create();
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($admin)
            ->post(route('admin.adverts.reject', $advert), [
                'reason' => '',
            ])
            ->assertSessionHasErrors('reason');
    }

    public function test_regular_user_cannot_reject(): void
    {
        $user   = User::factory()->create(['role' => User::ROLE_USER]);
        $advert = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($user)
            ->post(route('admin.adverts.reject', $advert), [
                'reason' => 'Sabab',
            ])
            ->assertForbidden();
    }

    // ==================== MODERATOR ACCESS ====================

    public function test_moderator_can_moderate(): void
    {
        $moderator = User::factory()->moderator()->create();
        $advert    = $this->createAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->actingAs($moderator)
            ->post(route('admin.adverts.moderate', $advert))
            ->assertRedirect();

        $this->assertTrue($advert->fresh()->isActive());
    }

    // ==================== HELPER ====================

    private function createAdvert(array $attrs = []): Advert
    {
        $user     = User::factory()->create();
        $category = Category::factory()->create();

        return Advert::create(array_merge([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'region_id'   => null,
            'title'       => 'Admin Test E\'lon',
            'content'     => 'Kontent',
            'price'       => 100000,
            'address'     => 'Tashkent',
            'status'      => Advert::STATUS_DRAFT,
        ], $attrs));
    }
}
