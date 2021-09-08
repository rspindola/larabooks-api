<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\{CompanyStoreRequest, CompanyUpdateRequest};
use App\Repositories\Api\V1\CompanyRepository;
use Exception;
class CompanyController extends Controller
{

    public function __construct(CompanyRepository $repository)
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
     * @param  App\Http\Requests\Api\Company\CompanyStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request)
    {
        $data = $request->all();

        try {
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $file = $request->logo;
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($company)
    {
        try {
            $result = $this->repository->find($company);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Company\CompanyUpdateRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, $company)
    {
        $data = $request->all();

        try {
            $companyFound = $this->repository->find($company);

            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $file = $request->logo;
            } else {
                $file = null;
            }

            $result = $this->repository->update($data, $companyFound, $file);

            // retornando sucesso
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($company)
    {
        try {
            $companyFound = $this->repository->find($company);
            $result = $this->repository->delete($companyFound);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
    }
}
