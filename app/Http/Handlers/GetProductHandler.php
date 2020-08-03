<?php

namespace App\Http\Handlers;

use App\Services\NutrientService;
use App\Http\Services\ScoringService;
use Illuminate\Foundation\Http\FormRequest;
use App\Orchestrators\YahooOrchestrator;

class GetProductHandler implements HandlerInterface
{
    /** @var YahooOrchestrator */
    protected $yahooOrchestrator;

    /** @var NutrientService */
    protected $nutrientService;

    /** @var ScoringService */
    protected $scoringService;

    public function __construct(YahooOrchestrator $yahooOrchestrator, NutrientService $nutrientService, ScoringService $scoringService)
    {
        $this->yahooOrchestrator = $yahooOrchestrator;
        $this->nutrientService   = $nutrientService;
        $this->scoringService    = $scoringService;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(FormRequest $request): array
    {
        $data = $request->only('jan');

        $products = $this->yahooOrchestrator->orchestrate($data);
        $product  = $this->nutrientService->getBestNutrient($products);
        $score    = $this->scoringService->getScore($product);

        return $score;
    }
}
