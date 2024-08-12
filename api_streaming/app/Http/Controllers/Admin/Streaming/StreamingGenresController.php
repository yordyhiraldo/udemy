<?php

namespace App\Http\Controllers\Admin\Streaming;

use Illuminate\Http\Request;
use App\Models\Streaming\Genre;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StreamingGenresController extends Controller
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

        $genres = Genre::filterGenres($search,$state)->orderBy("id","desc")->get();

        return response()->json([
            "message" => 200,
            "genres" => $genres->map(function($genre) {
                return [
                    "id" => $genre->id,
                    "title" => $genre->title,
                    "imagen" => env("APP_URL")."storage/".$genre->imagen,
                    "type" => $genre->type,
                    "state" => $genre->state,
                    "created_at" => $genre->created_at->format("Y-m-d h:i:s"),
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
        $genre_v = Genre::where("title",$request->title)->where("type",$request->type)->first();
        if($genre_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL GENERO YA EXISTE"
            ]);
        }

        if($request->hasFile("img")){
            $path = Storage::putFile("streaming/genres",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $genre = Genre::create($request->all());
        
        return response()->json([
            "message" => 200,
            "genre" => [
                "id" => $genre->id,
                "title" => $genre->title,
                "imagen" => env("APP_URL")."storage/".$genre->imagen,
                "type" => $genre->type,
                "state" => $genre->state,
                "created_at" => $genre->created_at->format("Y-m-d h:i:s"),
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
        $genre_v = Genre::where("id","<>",$id)->where("title",$request->title)->where("type",$request->type)->first();
        if($genre_v){
            return response()->json([
                "message" => 403,
                "message_text" => "EL GENERO YA EXISTE"
            ]);
        }

        $genre = Genre::findOrFail($id);

        if($request->hasFile("img")){
            if($genre->imagen){
                Storage::delete($genre->imagen);
            }
            $path = Storage::putFile("streaming/genres",$request->file("img"));
            $request->request->add(["imagen" => $path]);
        }

        $genre->update($request->all());
        return response()->json([
            "message" => 200,
            "genre" => [
                "id" => $genre->id,
                "title" => $genre->title,
                "imagen" => env("APP_URL")."storage/".$genre->imagen,
                "type" => $genre->type,
                "state" => $genre->state,
                "created_at" => $genre->created_at->format("Y-m-d h:i:s"),
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
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(["message" => 200]);
    }
}
