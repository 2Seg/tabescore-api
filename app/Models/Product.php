<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'jan',
        'name',
        'brand',
        'imageUrl',
        'energy',
        'protein',
        'lipid',
        'carbohydrate',
        'salt',
        'score',
    ];
}
