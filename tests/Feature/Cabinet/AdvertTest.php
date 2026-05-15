<?php

namespace Tests\Feature\Cabinet;

use App\Entity\Adverts\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdvertTest extends TestCase
{
    use RefreshDatabase;

    // ==================== INDEX ====================

    public function test_guest_cannot_access_adverts_index(): void
    {
        $this->get(route('cabinet.adverts.index'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_see_own_adverts_list(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Advert::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Mening e\'lonim',
            'content' => 'Content',
            'status' => Advert::STATUS_DRAFT,
        ]);

        $this->actingAs($user)
            ->get(route('cabinet.adverts.index'))
            ->assertOk()
            ->assertSee("Mening e'lonim");
    }

    public function test_user_cannot_see_other_users_adverts(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $category = Category::factory()->create();

        Advert::create([
            'user_id' => $other->id,
            'category_id' => $category->id,
            'title' => 'Boshqaning e\'loni',
            'content' => 'Content',
            'status' => Advert::STATUS_DRAFT,
        ]);

        $this->actingAs($user)
            ->get(route('cabinet.adverts.index'))
            ->assertOk()
            ->assertDontSee("Boshqaning e'loni");
    }

    // ==================== SHOW ====================

    public function test_owner_can_view_own_advert(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->get(route('cabinet.adverts.show', $advert))
            ->assertOk()
            ->assertSee($advert->title);
    }

    public function test_non_owner_gets_403_on_show(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $advert = $this->createAdvert($owner);

        $this->actingAs($other)
            ->get(route('cabinet.adverts.show', $advert))
            ->assertForbidden();
    }

    // ==================== PHOTOS ====================

    public function test_owner_can_view_photos_edit_page(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->get(route('cabinet.adverts.photos.edit', $advert))
            ->assertOk();
    }

    public function test_non_owner_cannot_view_photos_edit_page(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $advert = $this->createAdvert($owner);

        $this->actingAs($other)
            ->get(route('cabinet.adverts.photos.edit', $advert))
            ->assertForbidden();
    }

    public function test_owner_can_upload_photos(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->post(route('cabinet.adverts.photos.update', $advert),
                ['files' => [
                    UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg'),
                    UploadedFile::fake()->create('photo2.jpg', 100, 'image/jpeg'),
                ],
                ])
            ->assertRedirect(route('cabinet.adverts.show', $advert));

        $this->assertCount(2, $advert->fresh()->photos);
    }

    public function test_photo_upload_requires_image_files(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->post(route('cabinet.adverts.photos.update', $advert), [
                'files' => [
                    UploadedFile::fake()->create('document.pdf', 100),
                ],
            ])
            ->assertSessionHasErrors();
    }

    // ==================== SEND TO MODERATION ====================

    public function test_owner_can_send_draft_with_photo_to_moderation(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user, ['status' => Advert::STATUS_DRAFT]);
        $advert->photos()->create(['file' => 'adverts/test.png']);

        $this->actingAs($user)
            ->post(route('cabinet.adverts.send-to-moderation', $advert))
            ->assertRedirect(route('cabinet.adverts.show', $advert));

        $this->assertTrue($advert->fresh()->isModeration());
    }

    public function test_cannot_send_to_moderation_without_photo(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user, ['status' => Advert::STATUS_DRAFT]);

        $this->actingAs($user)
            ->post(route('cabinet.adverts.send-to-moderation', $advert))
            ->assertSessionHasErrors();
    }

    // ==================== DELETE ====================

    public function test_owner_can_delete_advert(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->delete(route('cabinet.adverts.destroy', $advert))
            ->assertRedirect(route('cabinet.adverts.index'));

        $this->assertNull(Advert::find($advert->id));
    }

    public function test_non_owner_cannot_delete_advert(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $advert = $this->createAdvert($owner);

        $this->actingAs($other)
            ->delete(route('cabinet.adverts.destroy', $advert))
            ->assertForbidden();

        $this->assertNotNull(Advert::find($advert->id));
    }

    // ==================== ATTRIBUTES ====================

    public function test_owner_can_view_attributes_edit_page(): void
    {
        $user = User::factory()->create();
        $advert = $this->createAdvert($user);

        $this->actingAs($user)
            ->get(route('cabinet.adverts.attributes.edit', $advert))
            ->assertOk();
    }

    // ==================== HELPER ====================

    private function createAdvert(User $user, array $attrs = []): Advert
    {
        $category = Category::factory()->create();

        return Advert::create(array_merge([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'region_id' => null,
            'title' => 'Test E\'lon',
            'content' => 'Test kontent',
            'price' => 50000,
            'address' => 'Tashkent',
            'status' => Advert::STATUS_DRAFT,
        ], $attrs));
    }
}
