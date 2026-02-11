<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = [];

    /**
     * The subscriptions that belong to the package.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }


    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'features' => 'json',
    ];
}
