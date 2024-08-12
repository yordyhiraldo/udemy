<?php

namespace App\Http\Controllers\Admin\Streaming;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Streaming\Tag;
use App\Models\Streaming\Actor;
use App\Models\Streaming\Genre;
use Owenoj\LaravelGetId3\GetId3;
use Vimeo\Laravel\Facades\Vimeo;
use App\Models\Streaming\Streaming;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Streaming\StreamingActor;
use App\Http\Resources\Streaming\StreamingResource;
use App\Http\Resources\Streaming\StreamingCollection;

class StreamingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get("search");
        $state = $request->get("state");

        $streamings = Streaming::filterStreamings($search,$state)->orderBy("id","desc")->get();

        return response()->json([
            "message" => 200,
            "streamings" => StreamingCollection::make($streamings),//->EL API RESOURCE
        ]);
    }

    function config_all() {
        
        $tags = Tag::where("state",1)->orderBy("id","desc")->get();

        $actors = Actor::where("state",1)->orderBy("id","desc")->get();

        $genres = Genre::where("state",1)->orderBy("id","desc")->get();

        return response()->json([
            "tags" => $tags,
            "actors" => $actors->map(function($actor) {
                return [
                    "id" => $actor->id,
                    "full_name" => $actor->full_name,
                    "profesion" => $actor->profesion,
                    "imagen" => env("APP_URL")."storage/".$actor->imagen,
                    "type" => $actor->type,
                    "state" => $actor->state,
                    "created_at" => $actor->created_at->format("Y-m-d h:i:s"),
                ];
            }),
            "genres" => $genres->map(function($actor) {
                return [
                    "id" => $actor->id,
                    "type" => $actor->type,
                    "title" => $actor->title,
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
        // error_log(json_encode($request->all()));
        // return ;
        $streaming_v = Streaming::where("title",$request->title)->first();
        if($streaming_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL STREAMING YA EXISTE"
            ]);
        }

        if($request->hasFile("img")){
            $path = Storage::putFile("streaming",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }
        // [2,3,4] -> 2,3,4
        // $request->request->add(["tags" => implode(",",$request->tags_a)])

        $request->request->add(["slug" => Str::slug($request->title)]);

        $streaming = Streaming::create($request->all());
        
        $streaming_actor = json_decode($request->actors_selected, true);

        foreach ($streaming_actor as $key => $streaming_ac) {
            StreamingActor::create([
                "streaming_id" => $streaming->id,
                "actor_id" =>$streaming_ac["id"],
            ]);
        }

        return response()->json([
            "message" => 200,
            "streaming" => StreamingResource::make($streaming),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $streaming = Streaming::find($id);
        if(!$streaming){
            return response()->json([
                "message" => 404,
                "message_text" => "EL STREAMING NO EXISTE", 
            ]);
        }
        return response()->json([
            "streaming" => StreamingResource::make($streaming),
        ]);
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
        $streaming_v = Streaming::where("id","<>",$id)->where("title",$request->title)->first();
        if($streaming_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL STREAMING YA EXISTE"
            ]);
        }

        $streaming = Streaming::findOrFail($id);

        if($request->hasFile("img")){
            if($streaming->imagen){
                Storage::delete($streaming->imagen);
            }
            $path = Storage::putFile("streaming",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $request->request->add(["slug" => Str::slug($request->title)]);

        $streaming->update($request->all());

        $streaming_actor = json_decode($request->actors_selected, true);

        foreach ($streaming->actors as $key => $actor_s) {
            $actor_s->delete();
        }

        foreach ($streaming_actor as $key => $streaming_ac) {
            StreamingActor::create([
                "streaming_id" => $streaming->id,
                "actor_id" =>$streaming_ac["id"],
            ]);
        }

        return response()->json([
            "message" => 200,
            "streaming" => StreamingResource::make($streaming),
        ]);
    }

   public function upload_video(Request $request, $id) {
        
        $time = 0;

       $track = new GetId3($request->file("video"));
        
       $time_video = $track->getPlaytimeSeconds();

       $response = Vimeo::upload($request->file("video"));
        // /videos/3434343 ["","videos",3434343]
        $vimeo_id = explode("/",$response)[2];

       $streaming = Streaming::findOrFail($id);

        $streaming->update(["vimeo_id" => $vimeo_id, "time" => date("H:i:s", $time_video)]);
        return response([
            "message" => 200,
           "vimeo_link" => "https://player.vimeo.com/video/".$vimeo_id
        ]);
   }

    public function upload_video_contenido(Request $request, $id) {
        
        $time = 0;

        $track = new GetId3($request->file("video"));
        
        $time_video = $track->getPlaytimeSeconds();

        $response = Vimeo::upload($request->file("video"));
        // /videos/3434343 ["","videos",3434343]
        $vimeo_id = explode("/",$response)[2];

        $streaming = Streaming::findOrFail($id);

        $streaming->update(["vimeo_contenido_id" => $vimeo_id, "time_contenido" => date("H:i:s", $time_video)]);
        return response([
            "message" => 200,
            "vimeo_link" => "https://player.vimeo.com/video/".$vimeo_id
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $streaming = Streaming::findOrFail($id);
        $streaming->delete();
        return response()->json(["message" => 200]);
    }
}


