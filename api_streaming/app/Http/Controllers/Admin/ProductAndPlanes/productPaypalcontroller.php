<?php

namespace App\Http\Controllers\Admin\ProductAndPlanes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product\productPaypal;
use App\Http\Controllers\Admin\Helper\paypalSubcription;

class productPaypalcontroller extends Controller
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
       // dd($this->paypalSubcription->getProducts());
        $search = $request->get("search");
        $products = productPaypal::where("name","like","%".$search."%")->orderBy("id", "desc")->get();
        return response()->json([
            "products" => $products->map(function($product) {
                return [
                    "id"  => $product->id,
                    "name"  => $product->name,
                    "type"  => $product->type,
                    "category"  => $product->category,
                    "description"  => $product->description,
                    "id_product_paypal"  => $product->id_product_paypal,
                    "created_at"  => $product->created_at instanceof \Carbon\Carbon ? $product->created_at->format("Y-m-d H:i:s") : null
                ];
            }),
        ]);
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
         $product =[
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'image_url' => 'https://avatars.githubusercontent.com/u/15802366?s=460&u=ac6cc646599f2ed6c4699a74b15192a29177f85a&v=4',
            'home_url' => 'https://github.com/leifermendez/laravel-paypal-subscription',
        ];
       $response = $this->paypalSubcription->storeProducts($product);
       $product["id_product_paypal"] = $response["id"];

       $product_paypal = productPaypal::create($product);
       
       return response()->json(["product"=> [
        "id"  => $product_paypal->id,
        "name"  => $product_paypal->name,
        "type"  => $product_paypal->type,
        "category"  => $product_paypal->category,
        "description"  => $product_paypal->description,
        "id_product_paypal"  => $product_paypal->id_product_paypal,
        "created_at"  => $product_paypal->created_at instanceof \Carbon\Carbon ? $product_paypal->created_at->format("Y-m-d H:i:s") : null
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
        dd($this->paypalSubcription->showProduct($id));
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
        $product =  [
               // [
              //  "op"=> "replace",
               // "path"=> "/name",
               // "value"=> $request->name,
               // ],
             //   [
              //  "op"=> "replace",
               // "path"=> "/type",
              //  "value"=> $request->type,
             //   ],
                [
                "op"=> "replace",
                "path"=> "/category",
                "value"=> $request->category,
                ],
                [
                "op"=> "replace",
                "path"=> "/description",
                "value"=> $request->description,
                ]
            ];

            $product_paypal = productPaypal::findOrFail($id);

            $response = $this->paypalSubcription->updateProducts($product_paypal->id_product_paypal,$product);

            $product_paypal-> update([
                "name"=> $request->name,
                "type"=> $request->type,
                "category"=> $request->category,
                "description"=> $request->description,
            ]);
            
            
            return response()->json(["product"=> [
                "id"  => $product_paypal->id,
                "name"  => $product_paypal->name,
                "type"  => $product_paypal->type,
                "category"  => $product_paypal->category,
                "description"  => $product_paypal->description,
                "id_product_paypal"  => $product_paypal->id_product_paypal,
                "created_at"  => $product_paypal->created_at instanceof \Carbon\Carbon ? $product_paypal->created_at->format("Y-m-d H:i:s") : null
            ]]);

        // dd($this->paypalSubcription->updateProducts($id,$product));
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
