<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid',
        'user_id',
        'category',
        'title',
        'slug',
        'image_uid',
        'ingredients',
        'short_desc',
        'long_desc',
        'status'
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];
}
