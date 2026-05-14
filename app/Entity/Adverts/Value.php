<?php

namespace App\Entity\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Value extends Model
{
    public $timestamps = false;

    protected $table = 'advert_advert_values';

    protected $fillable = ['attribute_id', 'value'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
