<?php

namespace App\Repositories\Api\V1;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class CompanyRepository
{
    public function getAll()
    {
        return CompanyResource::collection(Company::all());
    }

    public function find($company)
    {
        $companyFound = Company::find($company);

        if (!$companyFound) {
            throw new FileNotFoundException('Company not found', 404);
        }

        return new CompanyResource($companyFound);
    }

    public function create(array $data, $fileUpload)
    {
        if ($fileUpload) {
            // Define um aleatório para o arquivo baseado no timestamps atual
            $filename = $fileUpload->hashName();

            // Faz o upload:
            // Se funcionar o arquivo foi armazenado em storage/app/public/images/book/nomedinamicoarquivo.extensao
            $fileUpload->storeAs('images/companies', $filename, 'public');

            // inclui o nome novo no banco
            $data['logo'] = "images/companies/" . $filename;
        }

        $company = Company::create($data);
        return new CompanyResource($company);
    }

    public function update(array $data, $company, $fileUpload)
    {
        if ($fileUpload) {
            if (Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $filename = $fileUpload->hashName();
            $fileUpload->storeAs('images/companies', $filename, 'public');
            $data['logo'] = "images/companies/" . $filename;
        }

        $company->update($data);
        $company->refresh();
        return new CompanyResource($company);
    }

    public function delete($company)
    {
        if (Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();
        return ['success' => ['main' => 'Company deleted']];
    }
}
