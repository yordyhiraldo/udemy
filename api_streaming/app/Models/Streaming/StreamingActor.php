<?php

namespace App\Models\Streaming;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StreamingActor extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "streaming_id",
        "actor_id",
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

    function actor() {
        return $this->belongsTo(Actor::class);
    }
}

