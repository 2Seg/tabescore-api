<?php

namespace App\Http\Handlers\Product;

use App\Services\NutrientService;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ScoreService;
use App\Http\Resources\ProductResource;
use App\Http\Handlers\HandlerInterface;
use App\Http\Components\Product\Builder;
use Illuminate\Foundation\Http\FormRequest;
use App\Orchestrators\YahooOrchestrator;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ScoreHandler implements HandlerInterface
{
    /** @var YahooOrchestrator */
    protected $yahooOrchestrator;

    /** @var NutrientService */
    protected $nutrientService;

    /** @var ScoreService */
    protected $scoreService;

    /** @var Builder */
    protected $productBuilder;

    public function __construct(YahooOrchestrator $yahooOrchestrator, NutrientService $nutrientService, ScoreService $scoreService, Builder $productBuilder)
    {
        $this->yahooOrchestrator = $yahooOrchestrator;
        $this->nutrientService   = $nutrientService;
        $this->scoreService      = $scoreService;
        $this->productBuilder    = $productBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(FormRequest $request): JsonResource
    {
        $data = $request->only('jan');

        $productsData = $this->yahooOrchestrator->orchestrate($data);

        if (! $productsData->count() > 0) {
            throw new NotFoundHttpException(__('errors.' . JsonResponse::HTTP_NOT_FOUND));
        }

        $productData  = $this->nutrientService->getBestProduct($productsData);
        $scoreData    = $this->scoreService->getScore($productData);

        $product = $this->productBuilder->build(array_merge(
            $productData,
            ['score' => $scoreData]
        ));

        return ProductResource::make($product);
    }
}
