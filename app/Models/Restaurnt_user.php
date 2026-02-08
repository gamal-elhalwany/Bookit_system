<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Restaurnt_user extends Pivot
{
    protected $table = 'restaurnt_users';
    protected $guarded = [];
}
