<?php

namespace App\Parsers;

use Illuminate\Support\Arr;
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
     * Parse product info from yahoo product response
     *
     * @param  ResponseInterface $response
     * @return array
     */
    public function parseProductInfo(ResponseInterface $response): array
    {
        $product = collect(json_decode((string) $response->getBody(), true)['hits'][0]);

        return [
            'name'     => $product->get('name'),
            'brand'    => $product->get('brand')['name'],
            'imageUrl' => $product->get('image')['medium'],
        ];
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