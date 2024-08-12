<?php

namespace App\Http\Resources\StreamingFronted;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamingHomeResource extends JsonResource
{
   /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $actors = [];
        foreach ($this->resource->actors as $key => $actor_streaming) {
            array_push($actors,$actor_streaming->actor->full_name);
        }
        // ["JOSE","PEPITO"]
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "slug" => $this->resource->slug,
            "imagen" => env("APP_URL")."storage/".$this->resource->imagen,
            "subtitle" => $this->resource->subtitle,
            "description" => $this->resource->description,
            "genre_id" => $this->resource->genre_id,
            "genre" => [
                "id" => $this->resource->genre->id,
                "title" => $this->resource->genre->title,
            ] ,
            "avg_reviews" => $this->resource->avg_reviews ? round($this->resource->avg_reviews,2) : 0,
            "cal_total_time" => $this->resource->cal_total_time,
            "vimeo_id" => $this->resource->vimeo_id ? "https://player.vimeo.com/video/".$this->resource->vimeo_id : NULL,
            "time" => $this->resource->time,
            "vimeo_contenido_id" => $this->resource->vimeo_contenido_id ? "https://player.vimeo.com/video/".$this->resource->vimeo_contenido_id : NULL,
            "time_contenido" => $this->resource->time_contenido,
            "type" => $this->resource->type,
            "tags" => $this->resource->tags,
            "tags_multiples" => $this->resource->getTags(),
            "state" => $this->resource->state,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i:s"),
            "actors" => implode(",",$actors),//"JOSE","PEPITO",
            "created_format" => $this->resource->created_at->monthName. ' '.$this->resource->created_at->format('d').' '.$this->resource->created_at->format('Y')
        ];
    }
}
