<?php

namespace App\Models\Streaming;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StreamingSeason extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "streaming_id",
        "title",
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
    function streaming() {
        return $this->belongsTo(Streaming::class);
    }

    function episodes() {
        return $this->hasMany(StreamingEpisode::class);
    }

    function episode_actives() {
        return $this->hasMany(StreamingEpisode::class)->where("state",1);
    }
}
