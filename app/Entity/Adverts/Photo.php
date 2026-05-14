<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    public $timestamps = false;

    protected $table = 'advert_advert_photos';

    protected $fillable = ['file'];

    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class);
    }
}
