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
            'id'            => $this->id,
            'encrypt_id'    => encrypt($this->id),
            'image'         => ($this->image) ? $this->image->url : NULL,

            // 'title'      => $this->title,
            // 'body'       => $this->body,

            'dateForHumans' => $this->created_at->diffForHumans(),
            'created_at'     => $this->created_at
        ];
    }
}
