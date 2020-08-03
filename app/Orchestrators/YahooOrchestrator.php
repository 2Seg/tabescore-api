<?php

namespace App\Orchestrators;

use App\Http\Apis\YahooApi;
use App\Parsers\YahooParser;
use GuzzleHttp\Psr7\Response;
use App\Parsers\NutrientParser;
use Illuminate\Support\Collection;
use App\Http\Services\AsyncService;
use App\Http\Responses\YahooResponse;

class YahooOrchestrator implements OrchestratorInterface
{
    /** @var YahooApi */
    protected $yahooApi;

    /** @var YahooParser */
    protected $yahooParser;

    /** @var NutrientParser */
    protected $nutrientParser;

    /** @var AsyncService */
    protected $asyncService;

    public function __construct(YahooApi $yahooApi, YahooParser $yahooParser, NutrientParser $nutrientParser, AsyncService $asyncService)
    {
        $this->yahooApi       = $yahooApi;
        $this->yahooParser    = $yahooParser;
        $this->nutrientParser = $nutrientParser;
        $this->asyncService   = $asyncService;
    }

    public function orchestrate(array $data): Collection
    {
        $codes     = $this->yahooParser->parseCodes($this->yahooApi->getProducts($data['jan']));
        $products = collect();

        $this->asyncService->makePool(
            function () use ($codes) {
                foreach ($codes as $code) {
                    yield function () use ($code) {
                        return $this->yahooApi->getProductAsync($code);
                    };
                }
            },
            function (Response $response) use ($data, $products) {
                $products->add(array_merge(
                    $nutrients = $this->nutrientParser->parse($this->yahooParser->parseNutrientString($response)),
                    count($nutrients) > 0 ? ['jan' => $data['jan']] : [],
                    count($nutrients) > 0 ? $this->yahooParser->parseProductInfo($response) : []
                ));
            }
        )
            ->promise()
            ->wait();

        return $products->filter()->values();
    }
}