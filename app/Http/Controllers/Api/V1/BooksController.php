<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\{BookStoreRequest, BookUpdateRequest};
use App\Repositories\Api\V1\BookRepository;
use Exception;

class BooksController extends Controller
{
    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->repository->getAll();
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Api\Book\BookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStoreRequest $request)
    {
        $data = $request->all();

        try {
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $file = $request->cover;
            }else{
                $file = null;
            }

            $result = $this->repository->create($data, $file);

            // retornando sucesso
            return response()->json($result, 201);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        try {
            $result = $this->repository->find($book);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Book\BookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookUpdateRequest $request, $book)
    {
        $data = $request->all();

        try {
            $bookFound = $this->repository->find($book);

            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $file = $request->cover;
            } else {
                $file = null;
            }

            $result = $this->repository->update($data, $bookFound, $file);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($book)
    {
        try {
            $bookFound = $this->repository->find($book);
            $result = $this->repository->delete($bookFound);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }
}
