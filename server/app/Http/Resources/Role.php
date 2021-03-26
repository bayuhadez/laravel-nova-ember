<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Role extends JsonResource
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
            'type' => 'roles',
            'attributes' => [
                'name' => $this->name,
                'display-name' => $this->display_name,
                'description' => $this->description,
                'created-at' => $this->created_at,
                'updated-at' => $this->updated_at
            ]
        ];
    }
}
