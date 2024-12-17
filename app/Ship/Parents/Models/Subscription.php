<?php

declare(strict_types=1);

namespace App\Ship\Parents\Models;

use Database\Factories\ConsumerFactory;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'currency',
        'start_at',
        'pan',
        'period',
    ];

    /**
     * @return ConsumerFactory|null
     */
    // protected static function newFactory(): ?ConsumerFactory
    // {
    //     return ConsumerFactory::new();
    // }
}
