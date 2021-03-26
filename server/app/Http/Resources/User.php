<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\RoleRelationship as RoleRelationshipResource;

class User extends JsonResource
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
			'id' => $this->id,
			'type' => 'users',
			'attributes' => [
				'name' => $this->name,
				'email' => $this->email,
				'is-email-verified' => !empty($this->email_verified_at),
				'created-at' => $this->created_at,
				'updated-at' => $this->updated_at
			],
			'relationships' => [
				'roles' => [
					'data' => RoleRelationshipResource::collection($this->roles)
				]
			]
		];
	}

	public function with($request)
	{
		return [
			'included' => RoleResource::collection($this->roles)
		];
	}
}
