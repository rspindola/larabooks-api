<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\{DatabaseMigrations, RefreshDatabase};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
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
    public function testShouldGetCategories()
    {
        $request = $this->getJson(route('categories.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanAddCategory()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        Storage::fake('public');

        $fileSize = 1024; // 1mb
        $fileName = 'icon.png';
        $file = UploadedFile::fake()->create($fileName, $fileSize);

        $payload = [
            'name' => 'Category Test',
            'description' => 'Category description',
            'icon' => $file
        ];

        $request = $this->postJson(route('categories.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['data' => ["id", "name", "slug", "description", "created_at", "updated_at"]]);

        // Assert the file was stored...
        // Storage::disk('public')->assertExists('images/category/' . $file->hashName());
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantFindACategory()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->getJson(route('categories.show', ['category' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Categoria não encontrada']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanFindACategory()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        $request = $this->getJson(route('categories.show', ['category' => $category]));
        $request->assertStatus(200);
        $request->assertJsonStructure(['data' => ["id", "name", "slug", "description", "created_at", "updated_at"]]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantUpdateCategoryWhyNotFind()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;
        $payload = [
            'name' => 'Category Test Updated',
            'description' => 'Category description Updated'
        ];

        $request = $this->putJson(route('categories.update', ['category' => $payloadID]), $payload);
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Categoria não encontrada']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanUpdateACategory()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        $icon = UploadedFile::fake()->image('icon.jpg');

        $payload = [
            'name' => 'Category Test Updated',
            'description' => 'Category description Updated',
            'icon' => $icon
        ];

        $request = $this->putJson(route('categories.update', ['category' => $category->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['data' => ["name", "slug", "description", "created_at", "updated_at", "id"]]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantDeleteCategory()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->delete(route('categories.destroy', ['category' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Categoria não encontrada']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanDeleteCategory()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        $request = $this->delete(route('categories.destroy', ['category' => $category->id]));
        $request->assertStatus(200);
        $request->assertJson(['success' => ['main' => 'Category deleted']]);
    }
}
