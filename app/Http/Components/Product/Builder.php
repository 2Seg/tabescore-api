<?php

namespace App\Http\Components\Product;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use App\Http\Components\BuilderInterface;

class Builder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(array $data): Model
    {
        return new Product(Arr::only($data, [
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
        ]));
    }
}