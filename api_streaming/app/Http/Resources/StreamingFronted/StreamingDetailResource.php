<?php

namespace App\Http\Resources\StreamingFronted;

use Illuminate\Http\Resources\Json\JsonResource;

class StreamingDetailResource extends JsonResource
{
        /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

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
            "cal_total_time" => $this->resource->cal_total_time,
            "avg_reviews" => $this->resource->avg_reviews ? round($this->resource->avg_reviews,2) : 0,
            "vimeo_id" => $this->resource->vimeo_id ? "https://player.vimeo.com/video/".$this->resource->vimeo_id : NULL,
            "time" => $this->resource->time,
            "vimeo_contenido_id" => $this->resource->vimeo_contenido_id ? "https://player.vimeo.com/video/".$this->resource->vimeo_contenido_id : NULL,
            "time_contenido" => $this->resource->time_contenido,
            "type" => $this->resource->type,
            "tags" => $this->resource->tags,
            "tags_multiples" => $this->resource->getTags(),
            "state" => $this->resource->state,
            "created_at" => $this->resource->created_at->format("Y-m-d h:i:s"),
            "created_format" => $this->resource->created_at->format("Y/m"),
            "actors" => $this->resource->actors->map(function($actor_streaming) {
                return [
                    "id" => $actor_streaming->actor->id,
                    "full_name" => $actor_streaming->actor->full_name,
                    "profesion" => $actor_streaming->actor->profesion,
                    "imagen" => env("APP_URL")."storage/".$actor_streaming->actor->imagen,
                ];
            }),
            "count_seasons" => $this->resource->seasons->count(),
            "seasons" => $this->resource->seasons->map(function($season){
                return [
                    "id" => $season->id,
                    "title" => $season->title,
                    "episodes" => $season->episode_actives->map(function($episode){
                        
                        // October 1, 2020
                        return [
                            "id" => $episode->id,
                            "title" => $episode->title,
                            "imagen" => $episode->imagen ? env("APP_URL")."storage/".$episode->imagen : NULL,
                            "vimeo_id" => $episode->vimeo_id ? "https://player.vimeo.com/video/".$episode->vimeo_id : NULL,
                            "description" => $episode->description,
                            "cal_total_time" => $episode->cal_total_time,
                            "created_format" => $episode->created_at->monthName. ' '.$episode->created_at->format('d').' '.$episode->created_at->format('Y')
                        ];
                    })
                ];
            })
            ,
        ];
    }
}
