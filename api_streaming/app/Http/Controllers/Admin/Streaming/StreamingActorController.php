<?php

namespace App\Http\Controllers\Admin\Streaming;

use Illuminate\Http\Request;
use App\Models\Streaming\Actor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StreamingActorController extends Controller
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

    $actors = Actor::filterActors($search, $state)->orderBy("id", "desc")->get();

    return response()->json([
        "message" => 200,
        "actors" => $actors->map(function($actor) {
            return [
                "id" => $actor->id,
                "full_name" => $actor->full_name,
                "profesion" => $actor->profesion,
                "imagen" => env("APP_URL") . "storage/" . $actor->imagen,
                "type" => $actor->type,
                "state" => $actor->state,
                "created_at" => $actor->created_at->format("Y-m-d h:i:s"),
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
        $actor_v = Actor::where("full_name",$request->full_name)->where("type",$request->type)->first();
        if($actor_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL ACTOR YA EXISTE"
            ]);
        }

        if($request->hasFile("img")){
            $path = Storage::putFile("streaming/actors",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $actor = Actor::create($request->all());
        
        return response()->json([
            "message" => 200,
            "actor" => [
                "id" => $actor->id,
                "full_name" => $actor->full_name,
                "profesion" => $actor->profesion,
                "imagen" => env("APP_URL")."storage/".$actor->imagen,
                "type" => $actor->type,
                "state" => $actor->state,
                "created_at" => $actor->created_at->format("Y-m-d h:i:s"),
            ],
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
        $actor_v = Actor::where("id","<>",$id)->where("full_name",$request->full_name)->where("type",$request->type)->first();
        if($actor_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL ACTOR YA EXISTE"
            ]);
        }

        $actor = Actor::findOrFail($id);

        if($request->hasFile("img")){
            if($actor->imagen){
                Storage::delete($actor->imagen);
            }
            $path = Storage::putFile("streaming/actors",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $actor->update($request->all());
        return response()->json([
            "message" => 200,
            "actor" => [
                "id" => $actor->id,
                "full_name" => $actor->full_name,
                "profesion" => $actor->profesion,
                "imagen" => env("APP_URL")."storage/".$actor->imagen,
                "type" => $actor->type,
                "state" => $actor->state,
                "created_at" => $actor->created_at->format("Y-m-d h:i:s"),
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
        $actor = Actor::findOrFail($id);
        $actor->delete();
        return response()->json(["message" => 200]);
    }
}
