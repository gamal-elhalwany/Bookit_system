<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the restaurant for the subscription.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * The packages that belong to the subscription.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the payments for the subscription.
     */
    public function payments()
    {
        return $this->hasMany(SubscriptionsPayments::class, 'subscription_id');
    }
}
