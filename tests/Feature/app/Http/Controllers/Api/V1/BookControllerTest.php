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
    public function test_should_get_books()
    {
        Book::factory()->count(3)->create();
        $request = $this->getJson(route('books.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_book_with_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('book.jpg');

        $payload = [
            'company_id' => $book->company_id,
            'category_id' => $book->category_id,
            'title' => 'titulo de testes',
            'cover' => $file,
            'description' => 'descrição de testes',
            'about' => 'about de testes',
            'gender' => 'gênero de testes',
            'pages' => 100,
            'price' => 10.9,
            'status' => 1,
            'published_at' => '2021-10-03',
        ];

        $request = $this->postJson(route('books.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['title', 'cover', 'description', 'about', 'gender', 'pages', 'price', 'status', 'published_at', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/books/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_book_without_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $payload = [
            'company_id' => $book->company_id,
            'category_id' => $book->category_id,
            'title' => 'titulo de testes',
            'description' => 'descrição de testes',
            'about' => 'about de testes',
            'gender' => 'gênero de testes',
            'pages' => 100,
            'price' => 10.9,
            'status' => 1,
            'published_at' => '2021-10-03',
        ];

        $request = $this->postJson(route('books.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['title', 'cover', 'description', 'about', 'gender', 'pages', 'price', 'status', 'published_at', 'slug', 'dateForHumans', 'created_at']);
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_find_book()
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
    public function test_should_can_find_book()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $request = $this->getJson(route('books.show', ['book' => $book]));
        $request->assertStatus(200);
        $request->assertJsonStructure(['title', 'cover', 'description', 'about', 'gender', 'pages', 'price', 'status', 'published_at', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_book_not_send_data()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $payload = [];

        $request = $this->putJson(route('books.update', ['book' => $book->id]), $payload);
        $request->assertStatus(422);
        $request->assertJsonStructure(['message', 'errors']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_update_book_why_not_find()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payload = [
            'id' => 0,
            'company_id' => 0,
            'category_id' => 0,
            'title' => 'titulo de testes',
            'description' => 'descrição de testes',
            'about' => 'about de testes',
            'gender' => 'gênero de testes',
            'pages' => 100,
            'price' => 10.9,
            'status' => 1,
            'published_at' => '2021-10-03',
        ];

        $request = $this->putJson(route('books.update', ['book' => $payload['id']]), $payload);
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Book not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_book_with_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('book.jpg');

        $payload = [
            'title' => 'titulo de testes',
            'description' => 'descrição de testes',
            'about' => 'about de testes',
            'gender' => 'gênero de testes',
            'pages' => 100,
            'price' => 10.9,
            'status' => 1,
            'published_at' => '2021-10-03',
            'cover' => $file
        ];

        $request = $this->putJson(route('books.update', ['book' => $book->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['title', 'cover', 'description', 'about', 'gender', 'pages', 'price', 'status', 'published_at', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/books/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_book_without_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $book = Book::factory()->create();

        $payload = [
            'company_id' => $book->company_id,
            'category_id' => $book->category_id,
            'title' => 'titulo de testes',
            'description' => 'descrição de testes',
            'about' => 'about de testes',
            'gender' => 'gênero de testes',
            'pages' => 100,
            'price' => 10.9,
            'status' => 1,
            'published_at' => '2021-10-03',
        ];

        $request = $this->putJson(route('books.update', ['book' => $book->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['title', 'cover', 'description', 'about', 'gender', 'pages', 'price', 'status', 'published_at', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_delete_book()
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
    public function test_should_can_delete_book()
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
