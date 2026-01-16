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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

}
