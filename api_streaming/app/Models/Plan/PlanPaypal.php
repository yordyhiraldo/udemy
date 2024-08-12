<?php

namespace App\Models\Plan;

use App\Models\product\productPaypal;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanPaypal extends Model
{
    use HasFactory;
    protected $fillable =[
        "name",
        "description",
        "precio_mensual",
        "precio_anual",
        "month_free",
        "id_plan_paypal_mensual",
        "id_plan_paypal_anual",
        "id_product_paypal",
        "product_paypal_id"
        ];
    
        function setCreatedAtAttribute($value){
            date_default_timezone_set('America/Santo_Domingo');
        $this->attributes["created_at"]= Carbon::now();
        }
    
        function setUpdatedAtAttribute($value){
            date_default_timezone_set('America/Santo_Domingo');
            $this->attributes["updated_at"]= Carbon::now();
        }

        function product_paypal() {
          return $this->belongsTo(productPaypal::class);
        }
}
