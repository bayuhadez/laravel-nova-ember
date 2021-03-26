<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product as ProductResource;

class ProductCategoryRelationship extends JsonResource
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
            'company' => [
                'links' => [
                    'self' => route('product-categories.show', ['product-category' => $this->id]),
                    'related' => route('companies.show', ['company' => $this->company->id])
                ],
                'data' => [
                    'type' => 'company',
                    'id' => (string)$this->company->id,
                    'attributes' => [
                        'name' => $this->company->name,
                        'created_at' => $this->company->created_at,
                        'updated_at' => $this->company->updated_at
                    ]
                ]
            ],
            'products' => [
                'data' => ProductResource::collection($this->products),
            ]
		];
    }
}
