<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductCategoryRelationship;

class ProductCategory extends JsonResource
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
			'type' => 'product-category',
			'id' => (string)$this->id,
			'attributes' => [
                'name' => $this->name,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => new ProductCategoryRelationship($this),
            'links' => [
                'self' => route('product-categories.show', ['product-category' => $this->id]),
            ]
		];
    }
}
