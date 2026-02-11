<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     */
        protected $fillable = [
        'name',
        'job_title',
        'rate',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'comment' => 'json',
        'job_title' => 'json',
    ];

}
