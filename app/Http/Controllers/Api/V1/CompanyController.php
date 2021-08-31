<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends Controller
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
        return CompanyResource::collection(Company::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Api\Company\CompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $ext = $request->file('logo')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('logo')->storeAs('images/companies', $filename, 'public');
            $data['logo'] = "images/companies/" . $filename;
        }

        $company = Company::create($data);
        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($company)
    {
        $companyFound = Company::find($company);
        if (!$companyFound) {
            return response()->json(['errors' => ['main' => 'Company not found']], 404);
        }

        return new CompanyResource($companyFound);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\Company\CompanyRequest  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $company)
    {
        $companyFound = Company::find($company);
        if (!$companyFound) {
            return response()->json(['errors' => ['main' => 'Company not found']], 404);
        }

        $data = $request->all();

        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists($companyFound->logo)) {
                Storage::disk('public')->delete($companyFound->logo);
            }

            $ext = $request->file('logo')->getClientOriginalExtension();
            $filename = Str::random(10) . "." . $ext;
            $request->file('logo')->storeAs('images/companies', $filename, 'public');
            $data['logo'] = "images/companies/" . $filename;
        }

        $companyFound->update($data);
        $companyFound->refresh();

        return new CompanyResource($companyFound);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($company)
    {
        $companyFound = Company::find($company);
        if (!$companyFound) {
            return response()->json(['errors' => ['main' => 'Company not found']], 404);
        }

        if (Storage::disk('public')->exists($companyFound->logo)) {
            Storage::disk('public')->delete($companyFound->logo);
        }

        $companyFound->delete();
        return response()->json(['success' => ['main' => 'Company deleted']], 200);
    }
}
