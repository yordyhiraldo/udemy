<?php

namespace App\Http\Resources\StreamingFronted;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StreamingHomeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "data" => StreamingHomeResource::collection($this->collection),
        ];
    }
}
