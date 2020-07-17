<?php

namespace App\Parsers;

use Psr\Http\Message\ResponseInterface;

class YahooParser implements ParserInterface
{
    /**
     * Parse codes from yahoo products response
     *
     * @param  ResponseInterface $response
     * @return array
     */
    public function parseCodes(ResponseInterface $response): array
    {
        return collect(json_decode((string) $response->getBody(), true)['hits'])
            ->pluck('code')
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * Parce nutrient string from yahoo product response
     *
     * @param  ResponseInterface $response
     * @return string
     */
    public function parseNutrientString(ResponseInterface $response): string
    {
        return json_decode((string) $response->getBody(), true)['ResultSet'][0]['Result'][0]['SpAdditional'];
    }
}