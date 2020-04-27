<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ExceptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title'   => get_class($this->resource),
            'message' => $this->resource->getMessage(),
            $this->mergeWhen(config('app.env') !== 'production' && config('app.debug'), [
                'file'  => $this->resource->getFile(),
                'line'  => $this->resource->getLine(),
                'trace' => collect($this->resource->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ]),
        ];
    }

    public function resolve($request = null)
    {
        return ['error' => parent::resolve($request)];
    }
}
