<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * The restaurant current subscription
     */
    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    /**
     * A restaurant has many categories
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * A restaurant has many products (direct access)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * A restaurant has many images
     */
    public function images()
    {
        return $this->hasMany(RestaurantImage::class);
    }
}
