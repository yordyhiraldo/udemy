<?php

namespace App\Http\Controllers\Admin\Streaming;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Owenoj\LaravelGetId3\GetId3;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Storage;
use App\Models\Streaming\StreamingEpisode;

class StreamingEpisodesController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $episodes = StreamingEpisode::where("streaming_season_id",$request->get("streaming_season_id"))
                                    ->orderBy("id","desc")
                                    ->get();

        return response()->json([
            "episodes" => $episodes->map(function($episode){
                return [
                    "id" => $episode->id,
                    "title" => $episode->title,
                    "imagen" => $episode->imagen ? env("APP_URL")."storage/".$episode->imagen : NULL,
                    "description" => $episode->description,
                    "vimeo_id" =>  $episode->vimeo_id ? "https://player.vimeo.com/video/".$episode->vimeo_id : NULL,
                    "time" => $episode->time,
                    "state" => $episode->state,
                    "created_at" =>$episode->created_at->format("Y-m-d h:i:s"),
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
        $episode_v = StreamingEpisode::where("streaming_season_id",$request->streaming_season_id)
                                       ->where("title",$request->title)->first();
        if($episode_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL NOMBRE DEL EPISODIO YA EXISTE"
            ]);
        }

        if($request->hasFile("img")){
            $path = Storage::putFile("episodes",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $episode = StreamingEpisode::create($request->all());

        return response()->json([
            "message" => 200,
            "episode" => [
                "id" => $episode->id,
                "title" => $episode->title,
                "imagen" => $episode->imagen ? env("APP_URL")."storage/".$episode->imagen : NULL,
                "description" => $episode->description,
                "vimeo_id" =>  NULL,
                "time" => NULL,
                "state" => 1,
                "created_at" =>$episode->created_at->format("Y-m-d h:i:s"),
            ],
        ]);
    }

    function upload_video(Request $request,$id) {

        $track = new GetId3($request->file("video"));
        
        $time_video = $track->getPlaytimeSeconds();

        $response = Vimeo::upload($request->file("video"));
        // /videos/3434343 ["","videos",3434343]
        $vimeo_id = explode("/",$response)[2];

        $episode = StreamingEpisode::findOrFail($id);

        $episode->update(["vimeo_id" => $vimeo_id, "time" => date("H:i:s", $time_video)]);
        return response([
            "message" => 200,
            "vimeo_link" => "https://player.vimeo.com/video/".$vimeo_id
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
       $episode_v = StreamingEpisode::where("id","<>",$id)
                                       ->where("streaming_season_id",$request->streaming_season_id)
                                       ->where("title",$request->title)->first();
        if($episode_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL NOMBRE DEL EPISODIO YA EXISTE"
            ]);
        }

        $episode = StreamingEpisode::findOrFail($id);

        if($request->hasFile("img")){
            if($episode->imagen){
                Storage::delete($episode->imagen);
            }
            $path = Storage::putFile("episodes",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $episode->update($request->all());

        return response()->json([
            "message" => 200,
            "episode" => [
                "id" => $episode->id,
                "title" => $episode->title,
                "imagen" => $episode->imagen ? env("APP_URL")."storage/".$episode->imagen : NULL,
                "description" => $episode->description,
                "vimeo_id" =>  $episode->vimeo_id ? "https://player.vimeo.com/video/".$episode->vimeo_id : NULL,
                    "time" => $episode->time,
                    "state" => $episode->state,
                "created_at" =>$episode->created_at->format("Y-m-d h:i:s"),
            ],
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
        $episode = StreamingEpisode::findOrFail($id);
        $episode->delete();
        
        return response()->json([
            "message" => 200,
        ]);
    }
}
