<?php

namespace App\Services;

use Illuminate\Support\Collection;

class NutrientService
{
    public function getBestNutrient(Collection $nutrients): array
    {
        return $nutrients->reduce(function ($carry, $item) {
            if ($this->proof($carry) < abs($item['score'] ?? 0)) {
                return $carry;
            }

            return $item;
        });
    }

    protected function proof(?array $nutrient)
    {
        return abs(
            ($nutrient['energy'] ?? 0) - (
                ($nutrient['protein'] ?? 0) * 4
                + ($nutrient['lipid'] ?? 0) * 9
                + ($nutrient['carbohydrate'] ?? 0) * 4
            )
        );
    }
}