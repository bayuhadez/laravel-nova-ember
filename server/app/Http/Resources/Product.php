<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
			'type' => 'product',
			'id' => $this->id,
			'attributes' => [
				'name' => $this->name,
				'price' => $this->price,
				'image' => $this->image,
				'description' => $this->description,
			],
			'links' => [
				'self' => route('products.show', ['product' => $this->id]),
			]

		];
	}
}
