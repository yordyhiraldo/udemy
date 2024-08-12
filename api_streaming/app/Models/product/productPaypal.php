<?php

namespace App\Models\product;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productPaypal extends Model
{
    use HasFactory;
    protected $fillable =[
    "name",
    "type",
    "category",
    "description",
    "id_product_paypal"
    ];

    function setCreatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
    $this->attributes["created_at"]= Carbon::now();
    }

    function setUpdatedAtAttribute($value){
        date_default_timezone_set('America/Santo_Domingo');
        $this->attributes["updated_at"]= Carbon::now();
    }
}
