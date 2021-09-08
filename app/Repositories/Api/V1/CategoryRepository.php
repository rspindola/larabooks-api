<?php

namespace App\Repositories\Api\V1;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class CategoryRepository
{
    public function getAll()
    {
        return CategoryResource::collection(Category::all());
    }

    public function find($category)
    {
        $categoryFound = Category::find($category);

        if (!$categoryFound) {
            throw new FileNotFoundException('Category not found', 404);
        }

        return new CategoryResource($categoryFound);
    }

    public function create(array $data, $fileUpload)
    {
        if ($fileUpload) {
            // Define um aleatório para o arquivo baseado no timestamps atual
            $filename = $fileUpload->hashName();

            // Faz o upload:
            // Se funcionar o arquivo foi armazenado em storage/app/public/images/book/nomedinamicoarquivo.extensao
            $fileUpload->storeAs('images/categories', $filename, 'public');

            // inclui o nome novo no banco
            $data['icon'] = "images/categories/" . $filename;
        }

        $category = Category::create($data);
        return new CategoryResource($category);
    }

    public function update(array $data, $category, $fileUpload)
    {
        if ($fileUpload) {
            if (Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $filename = $fileUpload->hashName();
            $fileUpload->storeAs('images/categories', $filename, 'public');
            $data['icon'] = "images/categories/" . $filename;
        }

        $category->update($data);
        $category->refresh();
        return new CategoryResource($category);
    }

    public function delete($category)
    {
        if (Storage::disk('public')->exists($category->icon)) {
            Storage::disk('public')->delete($category->icon);
        }

        $category->delete();
        return ['success' => ['main' => 'Category deleted']];
    }
}
