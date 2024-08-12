<?php

namespace App\Http\Controllers\Admin\Helper;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;

class paypalSubcription { 

  public $client;
  public $AccessToken;
  public $URL_BASE;

  public function __construct() {
      
      $clientId = env("PAYPAL_CLIENT_ID");
      $secret = env("PAYPAL_SECRET");

      $this->URL_BASE = 'https://api-m.sandbox.paypal.com/v1/';
      $this->client = new GuzzleClient(["base_uri" => $this->URL_BASE]);
      $this->AccessToken = $this->getAccessToken($clientId,$secret);
  }

  private function getAccessToken($clientId,$secret){

      $response = $this->client->request('POST', 'oauth2/token', [
          "headers" => [
              "Accept" => "application/json",
              "Content-Type" => "application/x-www-form-urlencoded"
          ],
          "body" => "grant_type=client_credentials",
          "auth" => [
              $clientId, $secret, 'basic'
          ],
      ]);

      $response = json_decode($response->getBody(), true);
      return $response["access_token"];
  }

  // DEFINIR TODAS LAS DEMAS RUTAS

  public function getProducts() {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->get($this->URL_BASE."catalogs/products");
      return json_decode($response->getBody(), true);
  }

  public function storeProducts($data) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->post($this->URL_BASE."catalogs/products",$data);
      return json_decode($response->getBody(), true);
  }

  public function updateProducts($product_id,$data) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->patch($this->URL_BASE."catalogs/products/".$product_id,$data);
      return json_decode($response->getBody(), true);
  }

  public function showProduct($product_id) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->get($this->URL_BASE."catalogs/products/".$product_id);
      return json_decode($response->getBody(), true);
  }

  // PLANES

  public function getPlanes() {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->get($this->URL_BASE."billing/plans");
      return json_decode($response->getBody(), true);
  }

  public function storePlanes($plan) {
      $data = [
          "product_id" => $plan["product_id"],
          'name' => $plan["name"],
          'description' => $plan["description"],
          'status' => 'ACTIVE',
          'billing_cycles' =>  $plan["billing_cycles"],
          // [
          //     [
          //         'frequency' => [
          //             'interval_unit' => 'MONTH',
          //             'interval_count' => '1'
          //         ],
          //         'tenure_type' => 'REGULAR',
          //         'sequence' => '1',
          //         'total_cycles' => '12',
          //         'pricing_scheme' => [
          //             'fixed_price' => [
          //                 'value' => '3',
          //                 'currency_code' => 'USD'
          //             ]
          //         ]
          //     ]
          // ],
          'payment_preferences' => [
              'auto_bill_outstanding' => 'true',
              'setup_fee' => [
                  'value' => '0',
                  'currency_code' => 'USD'
              ],
              'setup_fee_failure_action' => 'CONTINUE',
              'payment_failure_threshold' => '3'
          ],
          'taxes' => [
              'percentage' => '10',
              'inclusive' => false
          ]
      ];
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->post($this->URL_BASE."billing/plans",$data);
      return json_decode($response->getBody(), true);
  }

  public function updatePlanes($plan_id,$data) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->patch($this->URL_BASE."billing/plans/".$plan_id,$data);
      return json_decode($response->getBody(), true);
  }

  public function showPlane($plan_id) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->get($this->URL_BASE."billing/plans/".$plan_id);
      return json_decode($response->getBody(), true);
  }

  // 
  
  public function showSubcription($subcription_id) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->get($this->URL_BASE."billing/subscriptions/".$subcription_id);
      return json_decode($response->getBody(), true);
  }

  public function cancelSubcription($subcription_id,$reason) {
      $response = Http::withHeaders([
          "Accept" => "application/json",
          "Content-Type" => "application/json",
          "Authorization" => "Bearer ".$this->AccessToken,
      ])->post($this->URL_BASE."billing/subscriptions/".$subcription_id."/cancel",$reason);
      return json_decode($response->getBody(), true);
  }
}