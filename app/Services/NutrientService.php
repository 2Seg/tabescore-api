<?php

namespace App\Services;

use Illuminate\Support\Collection;

class NutrientService
{
    public function getBestProduct(Collection $products): array
    {
        return $products->reduce(function ($carry, $item) {
            if ($this->proof($carry) < abs($item['score'] ?? 0)) {
                return $carry;
            }

            return $item;
        });
    }

    protected function proof(?array $product)
    {
        return abs(
            $product['energy'] ?? 0 - (
                $product['protein'] ?? 0 * 4
                + $product['lipid'] ?? 0 * 9
                + $product['carbohydrate'] ?? 0 * 4
            )
        );
    }
}