<?php

namespace App\Entity\Banner;

use App\Entity\Adverts\Category;
use App\Entity\Region\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int|null $region_id
 * @property string $name
 * @property int $limit
 * @property string $url
 * @property string $format
 * @property string|null $file
 * @property string $status
 * @property int $views
 * @property string|null $reject_reason
 * @property Carbon|null $published_at
 */
class Banner extends Model
{
    // Banner hayot sikli (statuslar)
    public const STATUS_DRAFT      = 'draft';       // yangi yaratilgan
    public const STATUS_MODERATION = 'moderation';  // moderatsiyaga yuborilgan
    public const STATUS_WAIT_PAY   = 'wait_pay';    // admin tasdiqladi, to'lov kutilmoqda
    public const STATUS_ACTIVE     = 'active';      // to'langan, ko'rsatilyapti
    public const STATUS_CLOSED     = 'closed';      // limit tugadi / yopildi

    protected $table   = 'banner_banners';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    // Format dropdown uchun ruxsat etilgan o'lchamlar
    public static function formatsList(): array
    {
        return [
            '240x400' => '240x400',
            '240x600' => '240x600',
            '728x90'  => '728x90',
            '300x250' => '300x250',
        ];
    }

    // ===== Status tekshiruvlari =====
    public function isDraft(): bool        { return $this->status === self::STATUS_DRAFT; }
    public function isOnModeration(): bool { return $this->status === self::STATUS_MODERATION; }
    public function isWaitPay(): bool      { return $this->status === self::STATUS_WAIT_PAY; }
    public function isActive(): bool       { return $this->status === self::STATUS_ACTIVE; }
    public function isClosed(): bool       { return $this->status === self::STATUS_CLOSED; }

    // ===== Biznes metodlar (hayot siklini boshqaradi) =====

    // User: moderatsiyaga yuborish (faqat draft bo'lsa va rasm yuklangan bo'lsa)
    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Banner is not draft.');
        }
        if (!$this->file) {
            throw new \DomainException('Upload the banner file first.');
        }
        $this->update(['status' => self::STATUS_MODERATION]);
    }

    // Admin: tasdiqlash → endi to'lov kutiladi
    public function moderate(): void
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Banner is not sent to moderation.');
        }
        $this->update([
            'status'        => self::STATUS_WAIT_PAY,
            'reject_reason' => null,
        ]);
    }

    // Admin: rad etish → draftga qaytadi, sababi saqlanadi
    public function reject(string $reason): void
    {
        $this->update([
            'status'        => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    // To'lov muvaffaqiyatli → banner faollashadi
    public function pay(Carbon $date): void
    {
        if (!$this->isWaitPay()) {
            throw new \DomainException('Banner is not waiting for payment.');
        }
        $this->update([
            'status'       => self::STATUS_ACTIVE,
            'published_at' => $date,
        ]);
    }

    // Har ko'rsatilganda chaqiriladi: views +1, limitga yetsa — yopiladi
    public function view(): void
    {
        $this->increment('views');
        if ($this->views >= $this->limit) {
            $this->update(['status' => self::STATUS_CLOSED]);
        }
    }

    // Rasmni almashtirish (Change File tugmasi)
    public function changeFile(string $file): void
    {
        $this->update(['file' => $file]);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // ===== Bog'lanishlar (relationships) =====
    public function user(): BelongsTo     { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function region(): BelongsTo   { return $this->belongsTo(Region::class); }

    // ===== Scope'lar (tez-tez ishlatadigan filtrlar) =====

    // Faqat aktiv bannerlar (saytda ko'rsatish uchun)
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Bitta userning bannerlari (kabinet ro'yxati uchun)
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
