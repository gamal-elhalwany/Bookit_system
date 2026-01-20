<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantImage extends Model
{
        protected $fillable = [
        'restaurant_id',
        'image',
    ];

    // علاقة الصورة بالمطعم
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
