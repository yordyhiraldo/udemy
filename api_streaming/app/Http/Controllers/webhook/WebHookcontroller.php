<?php

namespace App\Http\Controllers\webhook;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcription\Subcription;

class WebHookcontroller extends Controller
{
    function cancelled(Request $request) {
        $object_paypal = $request->all();
        $subcription_id = $object_paypal["resource"]["id"];
        
        $subcription = Subcription::where("subcription_paypal_id",$subcription_id)->orderBy("created_at","desc")->first();
        date_default_timezone_set("America/Santo_Domingo");
        if($subcription){
            $subcription->update([
                "renewal_cancelled" => 0,
                "renewal_cancelled_at" => now()
            ]);
        }
        return response()->json(["message" => 200]);
    }

    function payment(Request $request) {
       error_log(json_encode($request->all()));
       $object_paypal = $request->all();
       $subcription_id = $object_paypal["resource"]["billing_agreement_id"];
    
       $subcription = Subcription::where("subcription_paypal_id",$subcription_id)
                                  ->orderBy("created_at","desc")
                                  ->first();
         date_default_timezone_set("America/Santo_Domingo");

       if($subcription){

            $start_date = now();
            $end_date = null;

            if($subcription->type_plan == 1){
                $end_date = now()->addMonth();
            }else{
                $end_date = now()->addYear();
            }

            $createTime = Carbon::parse($object_paypal["resource"]["create_time"],'UTC')->tz("America/Lima");

            // start_date : 08/06 10:00:00 end_date : 09/06 10:00:00
            // create_time = 09/06/ 11:30:00
            $validate = $createTime->diffInMinutes($subcription->start_date) < $createTime->diffInMinutes($subcription->end_date);
            // 90 <  666666666666666 
            // 66666666666 < 90
                    
            if(!$validate){
                Subcription::create([
                    "user_id" => $subcription->user_id,
                    "subcription_paypal_id" => $subcription->subcription_paypal_id,
                    "plan_paypal_id" => $subcription->plan_paypal_id,
                    "paypal_plan_id" => $subcription->paypal_plan_id,
                    "type_plan" => $subcription->type_plan,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "price" => $object_paypal["resource"]["amount"]["total"],
                ]);
            }else{
                error_log("QUE EL REGISTRO DE SUBCRIPCIÓN YA EXISTE");
            }
 
       }

       return response()->json(["message" => 200]);
    }
}
