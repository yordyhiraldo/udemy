<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\User\UsersController;
use App\Http\Controllers\Admin\ProductAndPlanes\productPaypalcontroller;
use App\Http\Controllers\Admin\ProductAndPlanes\Plan\PlanPaypalController;
use App\Http\Controllers\Admin\Streaming\StreamingGenresController;
use App\Http\Controllers\Admin\Streaming\StreamingActorController;
use App\Http\Controllers\Admin\Streaming\StreamingTagController;
use App\Http\Controllers\Admin\Streaming\StreamingController;
use App\Http\Controllers\Admin\Streaming\StreamingSeasonController;
use App\Http\Controllers\Admin\Streaming\StreamingEpisodesController;
use App\Http\Controllers\Streaming\HomeStreamingController;
use App\Http\Controllers\Streaming\AuthStreamingController;
use App\Http\Controllers\webhook\WebHookcontroller;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::group([
 
    'middleware' => 'api',
    'prefix' => 'auth'
 
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login_ecommerce', [AuthController::class, 'login_ecommerce'])->name('login_ecommerce');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

 // MIS RUTAS PARA EL ADMIN
Route::group([
    'middleware' => 'auth:api',
], function ($router) {
    route::resource("users",UsersController::class);
    route::post("users/{id}",[UsersController::class,"update"]);
    route::resource("products",productPaypalcontroller::class);
    route::resource("planes",PlanPaypalController::class);
    Route::resource("genres",StreamingGenresController::class);
    Route::post("genres/{id}",[StreamingGenresController::class,"update"]);
    Route::resource("actors",StreamingActorController::class);
    Route::post("actors/{id}",[StreamingActorController::class,"update"]);
    Route::resource("tags",StreamingTagController::class);


    Route::get("streaming/config_all",[StreamingController::class,"config_all"]);
    Route::resource("streaming",StreamingController::class);
    Route::post("streaming/{id}",[StreamingController::class,"update"]);
    Route::post("streaming/upload_video/{id}",[StreamingController::class,"upload_video"]);
    Route::post("streaming/upload_video_contenido/{id}",[StreamingController::class,"upload_video_contenido"]);
    Route::resource("streaming_season",StreamingSeasonController::class);
    Route::resource("streaming_episode",StreamingEpisodesController::class);
    Route::post("streaming_episode/{id}",[StreamingEpisodesController::class,"update"]);
    Route::post("streaming_episode/upload_video/{id}",[StreamingEpisodesController::class,"upload_video"]);
    
});


//Route::group(["prefix" => "admin"], function($router){
//});

Route::group(["prefix" => "streaming_public"], function($router){
    Route::get("config_all",[HomeStreamingController::class,"config_all"]);
    Route::post("valid_register",[AuthStreamingController::class,"valid_register"]);  
    Route::post("register",[AuthStreamingController::class,"register"]);  
    Route::post("login_streaming",[AuthStreamingController::class,"login_streaming"]);
    Route::post("login_streaming_addtional",[AuthStreamingController::class,"login_streaming_addtional"]);
    
    Route::get("home",[HomeStreamingController::class,"home"]);
    

    Route::group([
        'middleware' => "confirmation",  //'auth:api',
    ], function ($router) {
        Route::get("show-streaming/{slug}",[HomeStreamingController::class,"show_streaming"]);
    });
});

Route::group(["prefix" => "webhook"], function($router){
    Route::post("cancelled",[WebHookcontroller::class,"cancelled"]);
    Route::post("payment",[WebHookcontroller::class,"payment"]);
});