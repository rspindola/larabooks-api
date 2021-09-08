<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\{CategoryUpdateRequest, CategoryStoreRequest};
use App\Repositories\Api\V1\CategoryRepository;
use Exception;

class CategoryController extends Controller
{

    public function __construct(CategoryRepository $repository)
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
     * @param  App\Http\Requests\Api\Category\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $data = $request->all();

        try {
            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $file = $request->icon;
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($category)
    {
        try {
            $result = $this->repository->find($category);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Category\CategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $category)
    {
        $data = $request->all();

        try {
            $categoryFound = $this->repository->find($category);

            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $file = $request->icon;
            }else{
                $file = null;
            }

            $result = $this->repository->update($data, $categoryFound, $file);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy ($category)
    {
        try {
            $categoryFound = $this->repository->find($category);
            $result = $this->repository->delete($categoryFound);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }
}
