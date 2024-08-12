<?php

namespace App\Http\Controllers\Admin\Streaming;

use App\Http\Controllers\Controller;
use App\Models\Streaming\Tag;
use Illuminate\Http\Request;

class StreamingTagController extends Controller
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

        $tags = Tag::filterTags($search,$state)->orderBy("id","desc")->get();

        return response()->json([
            "message" => 200,
            "tags" => $tags->map(function($tag) {
                return [
                    "id" => $tag->id,
                    "title" => $tag->title,
                    "type" => $tag->type,
                    "state" => $tag->state,
                    "created_at" => $tag->created_at->format("Y-m-d h:i:s"),
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
        $tag_v = Tag::where("title",$request->title)->where("type",$request->type)->first();
        if($tag_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL TAG YA EXISTE"
            ]);
        }

        $tag = Tag::create($request->all());
        
        return response()->json([
            "message" => 200,
            "tag" => [
                "id" => $tag->id,
                "title" => $tag->title,
                "type" => $tag->type,
                "state" => $tag->state,
                "created_at" => $tag->created_at->format("Y-m-d h:i:s"),
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
        $tag_v = Tag::where("id","<>",$id)->where("title",$request->title)->where("type",$request->type)->first();
        if($tag_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL TAG YA EXISTE"
            ]);
        }

        $tag = Tag::findOrFail($id);

        $tag->update($request->all());
        return response()->json([
            "message" => 200,
            "tag" => [
                "id" => $tag->id,
                "title" => $tag->title,
                "type" => $tag->type,
                "state" => $tag->state,
                "created_at" => $tag->created_at->format("Y-m-d h:i:s"),
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
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json(["message" => 200]);
    }
}
