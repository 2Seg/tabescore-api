<?php

namespace App\Http\Handlers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

interface HandlerInterface
{
    /**
     * Handle request
     *
     * @param  FormRequest $request
     * @return array
     */
    public function handle(FormRequest $request): array;
}