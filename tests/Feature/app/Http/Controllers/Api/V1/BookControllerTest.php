<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\{DatabaseMigrations, RefreshDatabase};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookControllerTest extends TestCase
{

    use DatabaseMigrations, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /**
     * Teste nao enviando credenciais.
     *
     * @return void
     */
    public function testShouldGetBooks()
    {
        $request = $this->getJson(route('books.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanAddBook()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();
        $payload = [
            'company_id' => $book->company_id,
            'category_id' => $book->category_id,
            'title' => $book->title,
            'cover' => $book->cover,
            'description' => $book->description,
            'about' => $book->about,
            'gender' => $book->gender,
            'pages' => $book->pages,
            'price' => $book->price,
            'status' => $book->status,
            'published_at' => $book->published_at,
        ];

        $request = $this->postJson(route('books.store'), $payload);
        $request->assertStatus(201);
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantFindABook()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->getJson(route('books.show', ['book' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Book not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanFindABook()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $request = $this->getJson(route('books.show', ['book' => $book]));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantUpdateBookWhyNotFind()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;
        $payload = [
            'name' => 'Book Test Updated',
            'description' => 'Book description Updated'
        ];

        $request = $this->putJson(route('books.update', ['book' => $payloadID]), $payload);
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Book not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanUpdateABook()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $payload = [
            'name' => 'Book Test Updated',
            'description' => 'Book description Updated'
        ];

        $request = $this->putJson(route('books.update', ['book' => $book->id]), $payload);
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantDeleteBook()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->delete(route('books.destroy', ['book' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Book not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanDeleteBook()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $request = $this->delete(route('books.destroy', ['book' => $book->id]));
        $request->assertStatus(200);
        $request->assertJson(['success' => ['main' => 'Book deleted']]);
    }
}
