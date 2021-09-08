<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BooksResource extends JsonResource
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
            'title'             => $this->title,
            'cover'             => ($this->cover) ? $this->cover : NULL,
            'description'       => $this->description,
            'about'             => $this->about,
            'gender'            => $this->gender,
            'pages'             => $this->pages,
            'price'             => $this->price,
            'status'            => ($this->status) ? "Ativo" : "Inativo",
            'published_at'      => $this->published_at,
            'slug'              => $this->slug,
            'dateForHumans'     => $this->created_at->diffForHumans(),
            'created_at'        => $this->created_at
        ];
    }
}
