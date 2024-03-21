<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequset;
use App\Http\Requests\UpdateTagRequset;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Tag::all();
        $tages = Tag::paginate(5);
        return response()->json($tages);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreTagRequset $request)
    {

        $data = $request->validated();
        $tag = Tag::create($data);
        return response()->json([

            'message' => 'Tag created successfully',
            'data' => ['tag' => $tag],
            'status' => true,
            'code' => 200
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateTagRequset $request, Tag $tag)
    {
        $validatedData= $request->validated();

        $tag->update($validatedData);

        return response()->json([

            'message' => 'Tag updated successfully',
            'data' => ['tag' => $tag],
            'status' => true,
            'code' => 200
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        //message
        return response()->json([

            'message' => 'Tag deleted successfully',
            'data' => [],
            'status' => true,
            'code' => 200
        ], 200);
    }
}
