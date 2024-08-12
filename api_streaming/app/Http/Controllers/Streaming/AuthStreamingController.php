<?php

namespace App\Http\Controllers\Streaming;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Plan\PlanPaypal;
use App\Http\Controllers\Controller;
use App\Models\Subcription\Subcription;
use App\Http\Controllers\Admin\Helper\PaypalSubcription;

class AuthStreamingController extends Controller
{
   
    //
    public $paypalSubcription;
    public function __construct(PaypalSubcription $paypalSubcription) {
        $this->paypalSubcription = $paypalSubcription;
    }
    
    function valid_register(Request $request) {
        
        $user = User::where("email",$request->email)->first();

        // $subcription = $this->paypalSubcription->showSubcription("I-1CXUBD3YY4P0");
        // return response()->json([
        //     "message" => 200,
        //     "message_text" => "EL USUARIO ESTA DISPONIBLE",
        //     "subcription" => $subcription,
        // ]);
        if($user){
            return response()->json([
                "message" => 403,
                "message_text" => "EL USUARIO YA EXISTE",
            ]);
        }

        return response()->json([
            "message" => 200,
            "message_text" => "EL USUARIO ESTA DISPONIBLE",
        ]);
    }

    function register(Request $request) {
        
        if($request->new_password){
            $request->request->add(["password" => bcrypt($request->new_password)]);
        }

        $user = User::create($request->all());

        date_default_timezone_set("America/Lima");
        $today = today();
        $end_date = null;

        // $subcription = $this->paypalSubcription->showSubcription($request->subcription_paypal_id);
        // $price = $subcription["shipping_amount"]["value"];
        $plan = PlanPaypal::findOrFail($request->plan_paypal_id);
        $price = 0;
        if($request->type_plan == 1){
            $price = $plan->precio_mensual + ($plan->precio_mensual*0.1);
            $end_date = now()->addMonth();
        }else{
            $price = $plan->precio_anual + ($plan->precio_anual*0.1);
            $end_date = now()->addYear();
        }
        Subcription::create([
            "user_id" => $user->id,
            "subcription_paypal_id" => $request->subcription_paypal_id,
            "plan_paypal_id" => $request->plan_paypal_id,
            "paypal_plan_id" => $request->paypal_plan_id,
            "type_plan" => $request->type_plan,
            "start_date" => $today,
            "end_date" => $end_date,
            "price" => $price,//
        ]);

        return response()->json([
            "message" => 200,
        ]);

    }
    
    function login_streaming(Request $request){

        $User = User::where("email",$request->email)->first();
        // CON LA EXISTENCIA DEL USUARIO
        if(!$User){
            return response()->json([
                "message" => 403,
                "message_text" => "EL USUARIO INGRESADO NO EXISTE",
            ]);
        }
        // CON LA RELACIÓN A UNA SUSCRIPCIÓN
        if(!$User->isHaveSubscription()){
            return response()->json([
                "message" => 405,
                "message_text" => "EL USUARIO NO TIENE UNA SUSCRIPCIÓN REGISTRADA",
            ]);
        }
        // CON LA RELACIÓN A UNA SUSCRIPCIÓN ACTIVA
        $subcription = $User->isActiveSubscription();
        if(!$subcription){
            return response()->json([
                "message" => 405,
                "message_text" => "EL USUARIO NO TIENE UNA SUSCRIPCIÓN ACTIVA",
            ]);
        }

        if (! $token = auth('api')->attempt(
            ["email" => $request->email,
            "password" => $request->password,
            "state" => 1,
            ]
            )) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
 
        return $this->respondWithToken($token,$subcription);

    }

    protected function respondWithToken($token,$subcription)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            "user" => [
                "full_name" =>  auth('api')->user()->name.' '.auth('api')->user()->surname,
                "email" => auth("api")->user()->email,
                "avatar" => auth("api")->user()->avatar ? env("APP_URL")."storage/".auth("api")->user()->avatar : NULL,
                "subcription" => [
                    "id" =>  $subcription->id,
                    "name_plan" => $subcription->plan_paypal_del->name,
                ],
            ]
        ]);
    }

    function login_streaming_addtional(Request $request){

        if (! $token = auth('api')->attempt(
            ["email" => $request->email,
            "password" => $request->password,
            "state" => 1,
            ]
            )) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        

        date_default_timezone_set("America/Santo_Domingo");
        $today = today();
        $end_date = null;
        
        $plan = PlanPaypal::findOrFail($request->plan_paypal_id);
        $price = 0;
        if($request->type_plan == 1){
            $price = $plan->precio_mensual + ($plan->precio_mensual*0.1);
            $end_date = now()->addMonth();
        }else{
            $price = $plan->precio_anual + ($plan->precio_anual*0.1);
            $end_date = now()->addYear();
        }
        $subcription = Subcription::create([
            "user_id" => auth('api')->user()->id,
            "subcription_paypal_id" => $request->subcription_paypal_id,
            "plan_paypal_id" => $request->plan_paypal_id,
            "paypal_plan_id" => $request->paypal_plan_id,
            "type_plan" => $request->type_plan,
            "start_date" => $today,
            "end_date" => $end_date,
            "price" => $price,//
        ]);

        return $this->respondWithToken($token,$subcription);
    }

}

