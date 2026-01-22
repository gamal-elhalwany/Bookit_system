<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'address',
        'phone',
        'email',
        'image',
        'opening_time',
        'closing_time',
        'rate',
        'subscription_id',
        'business_type',
        'subscription_end_date'
    ];

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
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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

}
