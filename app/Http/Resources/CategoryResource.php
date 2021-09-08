<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name'              => $this->name,
            'description'       => $this->description,
            'icon'              => ($this->icon) ? $this->icon : NULL,
            'slug'              => $this->slug,
            'dateForHumans'     => $this->created_at->diffForHumans(),
            'created_at'        => $this->created_at
        ];
    }
}
