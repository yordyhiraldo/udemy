<?php

namespace App\Http\Controllers\Streaming;

use Illuminate\Http\Request;
use App\Models\Streaming\Tag;
use App\Models\Plan\PlanPaypal;
use App\Models\Streaming\Genre;
use App\Models\Streaming\Review;
use App\Models\Streaming\Streaming;
use App\Http\Controllers\Controller;
use App\Http\Resources\StreamingFronted\StreamingHomeResource;
use App\Http\Resources\StreamingFronted\StreamingDetailResource;
use App\Http\Resources\StreamingFronted\StreamingHomeCollection;
use App\Http\Resources\StreamingFronted\StreamingSeriesHomeResource;

class HomeStreamingController extends Controller
{
    
    function config_all() {
        
        $plans = PlanPaypal::orderBy("id","desc")->get();
        return response()->json([
            "plans" => $plans->map(function($plan) {
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
            })
        ]);
    }

    function home() {
        $slider_home = Streaming::where("state",2)->inRandomOrder()->limit(3)->get();

        $last_movies = Streaming::where("state",2)->where("type",1)->inRandomOrder()->limit(5)->get();
        $last_videos = Streaming::where("state",2)->where("type",3)->inRandomOrder()->limit(5)->get();

        $top_10_home = Streaming::where("state",2)->inRandomOrder()->limit(10)->get();

        $last_tv_show = Streaming::where("state",2)->where("type",2)->inRandomOrder()->limit(5)->get();
        $movie_selected = Streaming::where("state",2)->where("type",1)->inRandomOrder()->limit(1)->first();


        $tv_show_section_special = Streaming::where("state",2)->where("type",2)->inRandomOrder()->limit(8)->get();

        return response()->json([
      //      "slider_home" => StreamingHomeCollection::make($slider_home),
     //       "last_movies" => StreamingHomeCollection::make($last_movies),
      //      "last_videos" => StreamingHomeCollection::make($last_videos),
      //      "top_10_home" => StreamingHomeCollection::make($top_10_home),

      //      "last_tv_show" => StreamingHomeCollection::make($last_tv_show),
      //      "movie_selected" => StreamingHomeResource::make($movie_selected),
//
        //    "tv_show_section_special" => $tv_show_section_special->map(function($tv_show_section){
      //          return StreamingSeriesHomeResource::make($tv_show_section);
       //     }),
        ]);
    }

    function show_streaming($slug) {
        
        $streaming = Streaming::where("slug",$slug)->where("state",2)->first();

        $user = auth("api")->user();

    //    $review = Review::where("user_id",$user->id)->where("streaming_id",$streaming->id)->first();
        
        return response()->json([
            "streaming" => StreamingDetailResource::make($streaming),
       //     "is_hava_review" => $review ? true : false,
        ]);

    }

    function genres(){

        $genres_movies = Genre::where("type",1)->where("state",1)->get();
        $genres_tv_show = Genre::where("type",2)->where("state",1)->get();
        $genres_video = Genre::where("type",3)->where("state",1)->get();

        return response()->json([
            "genres_movies" =>  $genres_movies->map(function($movie){
                return [
                    "id" => $movie->id,
                    "title" => $movie->title,
                    "imagen" => env("APP_URL")."storage/".$movie->imagen,
                ];
            }),
            "genres_tv_show" =>$genres_tv_show->map(function($movie){
                return [
                    "id" => $movie->id,
                    "title" => $movie->title,
                    "imagen" => env("APP_URL")."storage/".$movie->imagen,
                ];
            }),
            "genres_video" =>  $genres_video->map(function($movie){
                return [
                    "id" => $movie->id,
                    "title" => $movie->title,
                    "imagen" => env("APP_URL")."storage/".$movie->imagen,
                ];
            }),
        ]);
    }

    function filter_genres(Request $request){

        $genre_id = $request->genre_id;

        $streamigs = Streaming::where("genre_id",$genre_id)->where("state",2)->inRandomOrder()->limit(8)->get();

        return response()->json([
     //       "streamings" => StreamingHomeCollection::make($streamigs),
        ]);
    }

    function tags(){
        $tags_movies = Tag::select("id","title")->where("type",1)->where("state",1)->get();
        $tags_tv_show = Tag::select("id","title")->where("type",2)->where("state",1)->get();
        $tags_video = Tag::select("id","title")->where("type",3)->where("state",1)->get();

        return response()->json([
            "tags_movies" =>  $tags_movies,
            "tags_tv_show" => $tags_tv_show,
            "tags_video" =>  $tags_video,
        ]);
    }

    function filter_tags(Request $request){

        $tag_id = $request->tag_id;

        $tag = Tag::findOrFail($tag_id);

        $streamigs = Streaming::where("tags","like","%".$tag->title."%")->where("state",2)->inRandomOrder()->limit(8)->get();

        return response()->json([
       //     "streamings" => StreamingHomeCollection::make($streamigs),
        ]);
    }


    function demo() {
        return response()->json(["message" => 200]);
    }
}
