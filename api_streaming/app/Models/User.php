<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Subcription\Subcription;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        "surname",
        "type_user",
        "state",
        "role_id",
        "avatar",
        "date_birth",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    function role() {
        return $this->belongsTo(Role::class);
    }

    function subcriptions() {
        return $this->hasMany(Subcription::class);
    }

    function isHaveSubscription(){
       $subcription = $this->subcriptions->last();
       return $subcription ? true : false;
    }

    function isActiveSubscription(){
        if($this->isHaveSubscription()){
            $subcription = $this->subcriptions->last();
            if($subcription->renewal_cancelled == 0){
                return null;
            }else{
                date_default_timezone_set("America/Lima");
                if(Carbon::now()->between($subcription->start_date,Carbon::parse($subcription->end_date)->addDays(1))){
                    return $subcription;
                }else{
                    return null;
                }
            }
        }
        return null;
     }

    function scopeFilterUser($query,$search,$state) {
        if($search){
            $query->where("name","like","%".$search."%")
            ->orWhere("surname","like","%".$search."%")
            ->orWhere("email","like","%".$search."%");
        }
        if($state){
            $query->where("state",$state);
        }
        return $query;
    }
}
