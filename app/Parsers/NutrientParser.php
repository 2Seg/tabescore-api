<?php

namespace App\Parsers;

use Illuminate\Support\Str;

class NutrientParser implements ParserInterface
{
    // TODO: move these constants in model
    const ENERGY       = 'エネルギー';
    const PROTEIN      = 'たんぱく質';
    const LIPID        = '脂質';
    const CARBOHYDRATE = '炭水化物';
    const SODIUM       = '食塩相当量';

    const NUTRIENTS = [
        'energy'       => self::ENERGY,
        'protein'      => self::PROTEIN,
        'lipid'        => self::LIPID,
        'carbohydrate' => self::CARBOHYDRATE,
        'salt'         => self::SODIUM,
    ];

    public function parse($string): array
    {
        $nutrients = [];

        foreach (self::NUTRIENTS as $key => $nutrient) {
            if (Str::contains($string, $nutrient)) {
                $pat1 = "/$nutrient(([^\d]+)(\d+)(\.)(\d+)|([^\d]+)(\d+))/";
                $pat2 = "((\d+\.\d+|\d+))";

                preg_match($pat1, $string, $matches);
                preg_match($pat2, implode('', $matches), $matches2);

                if (count($matches2) > 0) {
                    $nutrients[$key] = (float) $matches2[0];
                }
            }
        }

        return isset(
            $nutrients['energy'],
            $nutrients['protein'],
            $nutrients['lipid'],
            $nutrients['carbohydrate']
        ) ? $nutrients : [];
    }
}