<?php

namespace App\Http\Controllers\Admin\ProductAndPlanes\Plan;


use Illuminate\Http\Request;
use App\Models\Plan\PlanPaypal;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Helper\paypalSubcription;

class PlanPaypalController extends Controller
{
    public $paypalSubcription;
    public function __construct(paypalSubcription $paypalSubcription) {
        $this->paypalSubcription = $paypalSubcription;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd($this->paypalSubcription->getPlanes());
        $search = $request->search;
        $plans = PlanPaypal::where("name","like","%".$search."%")->orderBy("id","desc")->get();

        return response()->json(["plans" => $plans->map(function($plan) {
            return [
                "id" => $plan->id,
                "name" => $plan->name,
                "description" => $plan->description,
                "precio_mensual" => $plan->precio_mensual,
                "precio_anual" => $plan->precio_anual,
                "month_free" => $plan->month_free,
                "id_plan_paypal_mensual" => $plan->id_plan_paypal_mensual,
                "id_plan_paypal_anual" => $plan->id_plan_paypal_anual,
                "id_product_paypal" => $plan->id_product_paypal,
                "product_paypal_id" => $plan->product_paypal_id,
                "product" => $plan->product_paypal,
                "created_at" => $plan->created_at->format("Y-m-d h:i:s"),
            ];
        })]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $plan_mensual = [];
        $plan_mensual["product_id"] = $request->id_product_paypal;
        $plan_mensual["name"] = $request->name . " - MENSUAL";
        $plan_mensual["description"] = $request->description. " - MENSUAL";
        $plan_mensual["billing_cycles"] = [];
        if($request->month_free > 0){
            array_push($plan_mensual["billing_cycles"],
                [
                    'frequency' => [
                        'interval_unit' => 'MONTH',
                        'interval_count' => '1'
                    ],
                    'tenure_type' => 'TRIAL',
                    'sequence' => '1',
                    'total_cycles' => $request->month_free,
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $request->precio_mensual,
                            'currency_code' => 'USD'
                        ]
                    ]
                ]
            );
        }
        array_push($plan_mensual["billing_cycles"],
            [
                'frequency' => [
                    'interval_unit' => 'MONTH',
                    'interval_count' => '1'
                ],
                'tenure_type' => 'REGULAR',
                'sequence' => $request->month_free > 0 ? '2' : '1',
                'total_cycles' => '12',
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value' => $request->precio_mensual,
                        'currency_code' => 'USD'
                    ]
                ]
            ]
        );
        
        $response_MENSUAL = $this->paypalSubcription->storePlanes($plan_mensual);
        // VIMEO -> 20 USD * 12 = 240 USD -> 12 USD * 12 = 144 USD
        $plan_anual = [];
        $plan_anual["product_id"] = $request->id_product_paypal;
        $plan_anual["name"] = $request->name . " - ANUAL";
        $plan_anual["description"] = $request->description. " - ANUAL";
        $plan_anual["billing_cycles"] = [];
        if($request->month_free > 0){
            array_push($plan_anual["billing_cycles"],
                [
                    'frequency' => [
                        'interval_unit' => 'MONTH',
                        'interval_count' => '1'
                    ],
                    'tenure_type' => 'TRIAL',
                    'sequence' => '1',
                    'total_cycles' => $request->month_free,
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $request->precio_anual,
                            'currency_code' => 'USD'
                        ]
                    ]
                ]
            );
        }
        array_push($plan_anual["billing_cycles"],
            [
                'frequency' => [
                    'interval_unit' => 'YEAR',
                    'interval_count' => '1'
                ],
                'tenure_type' => 'REGULAR',
                'sequence' => $request->month_free > 0 ? '2' : '1',
                'total_cycles' => '12',
                'pricing_scheme' => [
                    'fixed_price' => [
                        'value' => $request->precio_anual,
                        'currency_code' => 'USD'
                    ]
                ]
            ]
        );

        $response_ANUAL = $this->paypalSubcription->storePlanes($plan_anual);

        $request->request->add([
            "id_plan_paypal_mensual" => $response_MENSUAL["id"] ,
            "id_plan_paypal_anual" => $response_ANUAL["id"]
        ]);
        
        
        $planPaypal = PlanPaypal::create($request->all()); 

        return response()->json(["plan" => [
            "id" => $planPaypal->id,
            "name" => $planPaypal->name,
            "description" => $planPaypal->description,
            "product" => $planPaypal->product_paypal,
            "precio_mensual" => $planPaypal->precio_mensual,
            "precio_anual" => $planPaypal->precio_anual,
            "month_free" => $planPaypal->month_free,
            "id_plan_paypal_mensual" => $planPaypal->id_plan_paypal_mensual,
            "id_plan_paypal_anual" => $planPaypal->id_plan_paypal_anual,
            "id_product_paypal" => $planPaypal->id_product_paypal,
            "product_paypal_id" => $planPaypal->product_paypal_id,
            "created_at" => $planPaypal->created_at->format("Y-m-d h:i:s"),
        ]]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $planPaypal = PlanPaypal::findOrFail($id);

        $data_mensual = [
            [
                "op" => "replace",
                "path" => "/name",
                "value" => $request->name  . " - MENSUAL",
            ],
            [
                "op" => "replace",
                "path" => "/description",
                "value" => $request->description . " - MENSUAL",
            ]
            ];

        $response_MENSUAL = $this->paypalSubcription->updatePlanes($planPaypal->id_plan_paypal_anual,$data_mensual);
        
        $data_anual = [
            [
                "op" => "replace",
                "path" => "/name",
                "value" => $request->name ." - ANUAL",
            ],
            [
                "op" => "replace",
                "path" => "/description",
                "value" => $request->description ." - ANUAL",
            ]
        ];

        $response_ANUAL = $this->paypalSubcription->updatePlanes($planPaypal->id_plan_paypal_anual,$data_anual);
        
        $planPaypal->update($request->all()); 

        return response()->json(["plan" => [
            "id" => $planPaypal->id,
            "name" => $planPaypal->name,
            "description" => $planPaypal->description,
            "precio_mensual" => $planPaypal->precio_mensual,
            "precio_anual" => $planPaypal->precio_anual,
            "month_free" => $planPaypal->month_free,
            "id_plan_paypal_mensual" => $planPaypal->id_plan_paypal_mensual,
            "id_plan_paypal_anual" => $planPaypal->id_plan_paypal_anual,
            "id_product_paypal" => $planPaypal->id_product_paypal,
            "product_paypal_id" => $planPaypal->product_paypal_id,
            "product" => $planPaypal->product_paypal,
            "created_at" => $planPaypal->created_at->format("Y-m-d h:i:s"),
        ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
