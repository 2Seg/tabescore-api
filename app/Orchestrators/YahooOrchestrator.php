<?php

namespace App\Orchestrators;

use App\Http\Apis\YahooApi;
use App\Parsers\YahooParser;
use GuzzleHttp\Psr7\Response;
use App\Parsers\NutrientParser;
use App\Http\Services\AsyncService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function orchestrate(array $data): array
    {
        $response    = $this->yahooApi->getProducts($data['jan']);
        $codes       = $this->yahooParser->parseCodes($response);

        throw_unless(
            count($codes) > 0,
            NotFoundHttpException::class,
            __('errors.' . JsonResponse::HTTP_NOT_FOUND)
        );

        $product     = $this->yahooParser->parseProductInfo($response);
        $nutrients   = collect();

        $this->asyncService->makePool(
            function () use ($codes) {
                foreach ($codes as $code) {
                    yield function () use ($code) {
                        return $this->yahooApi->getProductAsync($code);
                    };
                }
            },
            function (Response $response) use ($nutrients) {
                if (count($nutrient = $this->nutrientParser->parse($this->yahooParser->parseNutrientString($response))) > 0) {
                    $nutrients->add($nutrient);
                }
            }
        )
            ->promise()
            ->wait();

        return array_merge([
            'jan'       => (int) $data['jan'],
            'nutrients' => $nutrients->filter()->values(),
        ], $product);
    }
}