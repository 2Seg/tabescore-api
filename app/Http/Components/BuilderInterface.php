<?php

namespace App\Http\Components;

use Illuminate\Database\Eloquent\Model;

interface BuilderInterface
{
    /**
     * Build a model from array data
     *
     * @param  array $data
     * @return Model
     */
    public function build(array $data): Model;
}