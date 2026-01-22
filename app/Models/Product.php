<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    /**
     * A product belongs to one restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * A product belongs to one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
