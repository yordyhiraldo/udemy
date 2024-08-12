<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            "id"=> $this->resource->id,
            "name"=> $this->resource->name,
            "surname"=> $this->resource->surname,
            "email"=> $this->resource->email,
            "state"=> $this->resource->state,
            "role"=> $this->resource->role,
            "avatar"=> $this->resource->avatar ? env("APP_URL")."storage/".$this->resource->avatar : "/assets/media/avatars/300-6.jpg",
            "created_at" => $this->resource->created_at->format("y-m-d h:i:s"),
        ];
    }
}
