<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'about'         => $this->about,
            'logo'          => ($this->logo) ? $this->logo : NULL,
            'website'       => $this->website,
            'slug'          => $this->slug,
            'dateForHumans' => $this->created_at->diffForHumans(),
            'created_at'    => $this->created_at
        ];
    }
}
