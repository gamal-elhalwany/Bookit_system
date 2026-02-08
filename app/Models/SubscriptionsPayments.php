<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionsPayments extends Model
{
    protected $table = 'subscriptions_payments';
    protected $guarded = [];

    /**
     * Get the subscription associated with the payment.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Get the restaurant associated with the payment.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
