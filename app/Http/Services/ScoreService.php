<?php

namespace App\Http\Services;

class ScoreService
{
    public function getScore(array $product): array
    {
        $lowerScore = stats_cdf_normal(0.75, 0, 1, 2);
        $upperScore = stats_cdf_normal(0.999, 0, 1, 2);
        $calories   = $this->convertNutrientToCalory($product);

        $lowerBounds = [];
        $lowerBounds[0] = 13 * 2300 / 100;
        $lowerBounds[1] = 30 * 2300 / 100;
        $lowerBounds[2] = 65 * 2300 / 100;
        $lowerBounds[3] = 8;

        $upperBounds = [];
        $upperBounds[0] = 20 * 2300 / 100;
        $upperBounds[1] = 20 * 2300 / 100;
        $upperBounds[2] = 50 * 2300 / 100;
        $upperBounds[3] = 0;

        $stdDis = [];
        $stdDis[0] = ($upperBounds[0] - $lowerBounds[0]) / ($upperScore - $lowerScore);
        $stdDis[1] = ($upperBounds[1] - $lowerBounds[1]) / ($lowerScore - $upperScore);
        $stdDis[2] = ($upperBounds[2] - $lowerBounds[2]) / ($lowerScore - $upperScore);
        $stdDis[3] = ($upperBounds[3] - $lowerBounds[3]) / ($lowerScore - $upperScore);

        $meanDis = [];
        $meanDis[0] = $lowerBounds[0] - ($lowerScore * $stdDis[0]);
        $meanDis[1] = $upperBounds[1] + ($upperScore * $stdDis[1]);
        $meanDis[2] = $upperBounds[2] + ($upperScore * $stdDis[2]);
        $meanDis[3] = $upperBounds[3] + ($upperScore * $stdDis[3]);

        $normScore = [];
        $normScore[0] = stats_cdf_normal($calories['protein'], $meanDis[0], $stdDis[0], 1);
        $normScore[1] = 1 - stats_cdf_normal($calories['lipid'], $meanDis[1], $stdDis[1], 1);
        $normScore[2] = 1 - stats_cdf_normal($calories['carbohydrate'], $meanDis[2], $stdDis[2], 1);
        $normScore[3] = 1 - stats_cdf_normal($calories['salt'], $meanDis[3], $stdDis[3], 1);

        $scores = [];
        $pointsAllocation = [30, 30, 30, 10];

        for ($i = 0; $i < 4; $i++)
            $scores[$i] = $normScore[$i] * $pointsAllocation[$i];

        $totalScore = array_sum($scores);

        return [
            'total'        => round($totalScore, 2),
            'protein'      => round($scores[0], 2),
            'lipid'        => round($scores[1], 2),
            'carbohydrate' => round($scores[2], 2),
            'salt'         => round($scores[3], 2),
        ];
    }

    protected function convertNutrientToCalory(array $nutrient): array
    {
        $coefKcal = 2300 / $nutrient['energy'];

        $nutrient['energy']       *= $coefKcal * 1;
        $nutrient['protein']      *= $coefKcal * 4;
        $nutrient['lipid']        *= $coefKcal * 9;
        $nutrient['carbohydrate'] *= $coefKcal * 4;
        $nutrient['salt']         *= $coefKcal * 1;

        return $nutrient;
    }
}
