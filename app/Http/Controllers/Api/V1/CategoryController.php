<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    function __construct()
    {
        // $this->middleware('permission:view_blogs', ['only' => ['index', 'show']]);
        // $this->middleware('permission:add_blogs',  ['only' => ['store']]);
        // $this->middleware('permission:edit_blogs', ['only' => ['update']]);
        // $this->middleware('permission:delete_blogs', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Api\Category\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('icon')) {
            $ext = $request->file('icon')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('icon')->storeAs('images/category', $filename, 'public');
            $data['icon'] = "images/category/" . $filename;
        }

        $category = Category::create($data);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        $categoryFound = Category::find($category);
        if (!$categoryFound) {
            return response()->json(['errors' => ['main' => 'Categoria não encontrada']], 404);
        }

        return new CategoryResource($categoryFound);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Category\CategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $category)
    {
        $categoryFound = Category::find($category);
        if (!$categoryFound) {
            return response()->json(['errors' => ['main' => 'Categoria não encontrada']], 404);
        }

        $data = $request->all();

        if ($request->hasFile('icon')) {
            if (Storage::disk('public')->exists($categoryFound->icon)) {
                Storage::disk('public')->delete($categoryFound->icon);
            }

            $ext = $request->file('icon')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('icon')->storeAs('images/category', $filename, 'public');
            $data['icon'] = "images/category/" . $filename;
        }

        $categoryFound->update($data);
        $categoryFound->refresh();

        return new CategoryResource($categoryFound);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy ($category)
    {
        $categoryFound = Category::find($category);
        if (!$categoryFound) {
            return response()->json(['errors' => ['main' => 'Categoria não encontrada']], 404);
        }

        if (Storage::disk('public')->exists($categoryFound->icon)) {
            Storage::disk('public')->delete($categoryFound->icon);
        }

        $categoryFound->delete();
        return response()->json(['success' => ['main' => 'Category deleted']], 200);
    }
}
