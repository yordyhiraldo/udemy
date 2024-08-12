<?php

namespace App\Models\Streaming;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "type",
        "state"
    ];

    function setCreatedAtAttribute($value) {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"]= Carbon::now();
    }

    function setUpdatedAtAttribute($value) {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"]= Carbon::now();
    }

    function scopefilterTags($query,$search,$state) {
        if($state){
            $query->where("state",$state);
        }
        if($search){
            $query->where("title","like","%".$search."%");
        }
        return $query;
    }
}

