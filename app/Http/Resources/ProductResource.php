<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id'           => $this->id,
            'jan'          => $this->jan,
            'name'         => $this->name,
            'brand'        => $this->brand,
            'imageUrl'     => $this->imageUrl,
            'nutrients'    => [
                'energy'       => $this->energy,
                'protein'      => $this->protein,
                'lipid'        => $this->lipid,
                'carbohydrate' => $this->carbohydrate,
                'salt'         => $this->salt,
            ],
            'score'        => $this->score,
            'createdAt'    => $this->createdAt,
            'updatedAt'    => $this->updatedAt,
        ];
    }
}
