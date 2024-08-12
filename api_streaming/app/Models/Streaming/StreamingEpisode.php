<?php

namespace App\Models\Streaming;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StreamingEpisode extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "streaming_season_id",
        "title",
        "description",
        "imagen",
        "vimeo_id",
        "time",
        "state",
    ];
    function setCreatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
    $this->attributes["created_at"]= Carbon::now();
    }

    function setUpdatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
        $this->attributes["updated_at"]= Carbon::now();
    }
    function season() {
        return $this->belongsTo(StreamingSeason::class);
    }

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
        $times = [$this->time];
        return $this->AddTimes($times);
    }
}

