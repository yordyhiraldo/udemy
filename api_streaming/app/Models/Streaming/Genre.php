<?php

namespace App\Models\Streaming;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "imagen",
        "type",
        "state"
    ];
    function setCreatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
    $this->attributes["created_at"]= Carbon::now();
    }

    function setUpdatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
        $this->attributes["updated_at"]= Carbon::now();
    }

    function scopefilterGenres($query,$search,$state) {
        if($state){
            $query->where("state",$state);
        }
        if($search){
            $query->where("title","like","%".$search."%");
        }
        return $query;
    }
}
