<?php

namespace App\Models\Streaming;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "full_name",
        "imagen",
        "type",
        "state",
        "profesion",
    ];

    public static function filterActors($search = null, $state = null)
{
    $query = self::query();

    if ($search) {
        $query->where('full_name', 'like', "%{$search}%");
    }

    if ($state) {
        $query->where('state', $state);
    }

    return $query;
}

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
            $query->where("full_name","like","%".$search."%");
        }
        return $query;
    }
}
