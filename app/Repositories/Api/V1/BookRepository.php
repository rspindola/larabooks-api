<?php

namespace App\Repositories\Api\V1;

use App\Http\Resources\BooksResource;
use App\Models\Book;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class BookRepository
{
    public function getAll()
    {
        return BooksResource::collection(Book::all());
    }

    public function find($book)
    {
        $bookFound = Book::find($book);

        if (!$bookFound) {
            throw new FileNotFoundException('Book not found', 404);
        }

        return new BooksResource($bookFound);
    }

    public function create(array $data, $fileUpload)
    {
        if ($fileUpload) {
            // Define um aleatório para o arquivo baseado no timestamps atual
            $filename = $fileUpload->hashName();

            // Faz o upload:
            // Se funcionar o arquivo foi armazenado em storage/app/public/images/book/nomedinamicoarquivo.extensao
            $fileUpload->storeAs('images/books', $filename, 'public');

            // inclui o nome novo no banco
            $data['cover'] = "images/books/" . $filename;
        }

        $book = Book::create($data);
        return new BooksResource($book);
    }

    public function update(array $data, $book, $fileUpload)
    {
        if ($fileUpload) {
            if (Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $filename = $fileUpload->hashName();
            $fileUpload->storeAs('images/books', $filename, 'public');
            $data['cover'] = "images/books/" . $filename;
        }

        $book->update($data);
        $book->refresh();
        return new BooksResource($book);
    }

    public function delete($book)
    {
        if (Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();
        return ['success' => ['main' => 'Book deleted']];
    }
}
