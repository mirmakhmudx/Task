<?php

namespace Tests\Unit\Entity;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Adverts\Photo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdvertTest extends TestCase
{
    use RefreshDatabase;

    // ==================== STATUS CHECKS ====================

    public function test_new_advert_is_draft(): void
    {
        $advert = $this->makeAdvert();

        $this->assertTrue($advert->isDraft());
        $this->assertFalse($advert->isModeration());
        $this->assertFalse($advert->isActive());
        $this->assertFalse($advert->isClosed());
    }

    // ==================== SEND TO MODERATION ====================

    public function test_send_to_moderation_requires_draft_status(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_MODERATION]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Advert is not draft.');

        $advert->sendToModeration();
    }

    public function test_send_to_moderation_requires_at_least_one_photo(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_DRAFT]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Upload at least one photo.');

        $advert->sendToModeration();
    }

    public function test_send_to_moderation_succeeds_with_photo(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_DRAFT]);
        $advert->photos()->create(['file' => 'adverts/test.png']);

        $advert->sendToModeration();

        $this->assertTrue($advert->isModeration());
    }

    // ==================== MODERATE ====================

    public function test_moderate_requires_moderation_status(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_DRAFT]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Advert is not sent to moderation.');

        $advert->moderate(Carbon::now());
    }

    public function test_moderate_sets_active_status_and_dates(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_MODERATION]);
        $now = Carbon::parse('2026-05-15 10:00:00');

        $advert->moderate($now);

        $this->assertTrue($advert->isActive());
        $this->assertEquals('2026-05-15', $advert->published_at->toDateString());
        $this->assertEquals('2026-06-14', $advert->expires_at->toDateString());
    }

    // ==================== REJECT ====================

    public function test_reject_sets_draft_status_and_reason(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_MODERATION]);

        $advert->reject('Qoidalarga zid kontent.');

        $this->assertTrue($advert->isDraft());
        $this->assertEquals('Qoidalarga zid kontent.', $advert->reject_reason);
    }

    // ==================== EXPIRE ====================

    public function test_expire_requires_active_status(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_DRAFT]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Advert is not active.');

        $advert->expire();
    }

    public function test_expire_sets_closed_status(): void
    {
        $advert = $this->makeAdvert(['status' => Advert::STATUS_ACTIVE]);

        $advert->expire();

        $this->assertTrue($advert->isClosed());
    }

    // ==================== IS OWNED BY ====================

    public function test_is_owned_by_correct_user(): void
    {
        $user   = User::factory()->create();
        $advert = $this->makeAdvert(['user_id' => $user->id]);

        $this->assertTrue($advert->isOwnedBy($user));
    }

    public function test_is_not_owned_by_another_user(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $advert = $this->makeAdvert(['user_id' => $owner->id]);

        $this->assertFalse($advert->isOwnedBy($other));
    }

    // ==================== HELPER ====================

    private function makeAdvert(array $attrs = []): Advert
    {
        $user     = User::factory()->create();
        $category = Category::factory()->create();

        return Advert::create(array_merge([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'region_id'   => null,
            'title'       => 'Test advert',
            'content'     => 'Test content',
            'price'       => 10000,
            'address'     => 'Tashkent',
            'status'      => Advert::STATUS_DRAFT,
        ], $attrs));
    }
}
