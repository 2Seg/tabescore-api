<?php

namespace App\Http\Handlers\Product;

use App\Services\NutrientService;
use App\Http\Services\ScoreService;
use App\Http\Resources\ProductResource;
use App\Http\Handlers\HandlerInterface;
use App\Http\Components\Product\Builder;
use Illuminate\Foundation\Http\FormRequest;
use App\Orchestrators\YahooOrchestrator;
use Illuminate\Http\Resources\Json\JsonResource;

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

        $productArray = $this->yahooOrchestrator->orchestrate($data);

        $productArray = array_merge($productArray, [
            'nutrients' => $productArray['nutrients']->count() > 0
                ? $nutrient = $this->nutrientService->getBestNutrient($productArray['nutrients']) : null,
            'score'     => $productArray['nutrients']->count() > 0
                ? $this->scoreService->getScore($nutrient) : null,
        ]);

        $product = $this->productBuilder->build($productArray);

        return ProductResource::make($product);
    }
}
