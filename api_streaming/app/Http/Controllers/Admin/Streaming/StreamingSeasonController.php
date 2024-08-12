<?php

namespace App\Http\Controllers\Admin\Streaming;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Streaming\StreamingSeason;

class StreamingSeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $seasons = StreamingSeason::where("streaming_id",$request->get("streaming_id"))->orderBy("id","desc")->get();

        return response()->json([
            "message" => 200,
            "seasons" => $seasons->map(function($season) {
                return [
                    "id" => $season->id,
                    "title" => $season->title,
                    "state" => $season->state,
                    "episodios" => $season->episodes->count(),
                    "created_at" => $season->created_at->format("Y-m-d h:i:s"),
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
        $season_v = StreamingSeason::where("title",$request->title)->where("streaming_id",$request->streaming_id)->first();
        if($season_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL SEASON YA EXISTE"
            ]);
        }

        $season = StreamingSeason::create($request->all());
        
        return response()->json([
            "message" => 200,
            "season" => [
                "id" => $season->id,
                "title" => $season->title,
                "state" => 1,
                "episodios" => $season->episodes->count(),
                "created_at" => $season->created_at->format("Y-m-d h:i:s"),
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
        $season_v = StreamingSeason::where("id","<>",$id)->where("title",$request->title)->where("streaming_id",$request->streaming_id)->first();
        if($season_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL SEASON YA EXISTE"
            ]);
        }

        $season = StreamingSeason::findOrFail($id);

        $season->update($request->all());
        return response()->json([
            "message" => 200,
            "season" => [
                "id" => $season->id,
                "title" => $season->title,
                "state" => $season->state,
                "episodios" => $season->episodes->count(),
                "created_at" => $season->created_at->format("Y-m-d h:i:s"),
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
        $season = StreamingSeason::findOrFail($id);
        $season->delete();
        return response()->json(["message" => 200]);
    }
}
