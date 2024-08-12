<?php

namespace App\Models\Streaming;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Streaming extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "slug",
        "imagen",
        "subtitle",
        "description",
        "genre_id",
        "vimeo_id",
        "time",
        "tags",//SUSPESO,COMEDIA,TERROR
        "state",
        "type",

        "vimeo_contenido_id",
        "time_contenido",
    ];

    function setCreatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
    $this->attributes["created_at"]= Carbon::now();
    }

    function setUpdatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
        $this->attributes["updated_at"]= Carbon::now();
    }

    function genre() {
        return $this->belongsTo(Genre::class);
    }

    function actors() {
        return $this->hasMany(StreamingActor::class);
    }

    function seasons() {
        return $this->hasMany(StreamingSeason::class);
    }

   // function reviews() {
     //   return $this->hasMany(review::class);
  //  }

    function getAvgReviewsAttribute() {
        return $this->reviews->avg("rating");
    }

    function getTags() {
        // 5,6,7 -> [5,6,7]
        $tags = explode(",",$this->tags);
        $tags_model = Tag::whereIn("title",$tags)->get();
        return $tags_model;
    }
    
    function scopefilterStreamings($query,$search,$state) {
        if($state){
            $query->where("state",$state);
        }
        if($search){
            $query->where("title","like","%".$search."%");
        }
        return $query;
    }
    // ["02:02:56","02:02:56"]
    function AddTimes($horas)
    {
        $total = 0;
        foreach($horas as $h) {
            $parts = explode(":", $h);
            $total += $parts[2] + $parts[1]*60 + $parts[0]*3600;
        }
        $hours = floor($total / 3600);
        $minutes = floor(($total / 60) % 60);
        $seconds = $total % 60;

        return $hours." hrs ".$minutes." mins";
    }

    function getCalTotalTimeAttribute() {
        $times = [];
        if($this->type == 1 || $this->type == 3){
            return $this->AddTimes([$this->time_contenido]);
        }
        if($this->type == 2){
            foreach ($this->seasons as $key => $season) {
                foreach ($season->episodes as $key => $episode) {
                    array_push($times,$episode->time);
                }
            }
            return $this->AddTimes($times);
        }
        return $this->AddTimes($times);
    }
}
