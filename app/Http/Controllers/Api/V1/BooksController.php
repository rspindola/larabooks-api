<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BooksRequest;
use App\Models\Book;
use App\Http\Resources\BooksResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BooksController extends Controller
{
    public function __construct()
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
        return BooksResource::collection(Book::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Api\Book\BookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BooksRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('cover')) {
            $ext = $request->file('cover')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('cover')->storeAs('images/book', $filename, 'public');
            $data['cover'] = "images/book/" . $filename;
        }

        $book = Book::create($data);
        return new BooksResource($book);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        $bookFound = Book::find($book);
        if (!$bookFound) {
            return response()->json(['errors' => ['main' => 'Book not found']], 404);
        }

        return new BooksResource($bookFound);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Book\BookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BooksRequest $request, $book)
    {
        $bookFound = Book::find($book);
        if (!$bookFound) {
            return response()->json(['errors' => ['main' => 'Book not found']], 404);
        }

        $data = $request->all();

        if ($request->hasFile('cover')) {
            if (Storage::disk('public')->exists($bookFound->cover)) {
                Storage::disk('public')->delete($bookFound->cover);
            }

            $ext = $request->file('cover')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('cover')->storeAs('images/book', $filename, 'public');
            $data['cover'] = "images/book/" . $filename;
        }

        $bookFound->update($data);
        $bookFound->refresh();

        return new BooksResource($bookFound);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($book)
    {
        $bookFound = Book::find($book);
        if (!$bookFound) {
            return response()->json(['errors' => ['main' => 'Book not found']], 404);
        }

        if (Storage::disk('public')->exists($bookFound->cover)) {
            Storage::disk('public')->delete($bookFound->cover);
        }

        $bookFound->delete();
        return response()->json(['success' => ['main' => 'book deleted']], 200);
    }
}
