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
    public function test_should_get_categories()
    {
        $request = $this->getJson(route('categories.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados incorretos.
     *
     * @return void
     */
    public function test_should_cant_add_category_not_send_credentials()
    {
        $payload = [
            'name' => 'Category Test',
            'description' => 'Category description'
        ];

        $request = $this->postJson(route('categories.store'), $payload);
        $request->assertStatus(401);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_category_with_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        Storage::fake('public');
        $file = UploadedFile::fake()->image('category.jpg');

        $payload = [
            'name' => 'Category Test',
            'description' => 'Category description',
            'icon' => $file
        ];

        $request = $this->postJson(route('categories.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['name', 'description', 'icon', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/categories/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_category_without_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payload = [
            'name' => 'Category Test',
            'description' => 'Category description'
        ];

        $request = $this->postJson(route('categories.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['name', 'description', 'icon', 'slug', 'dateForHumans', 'created_at']);
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_find_category()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->getJson(route('categories.show', ['category' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Category not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_find_category()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        $request = $this->getJson(route('categories.show', ['category' => $category]));
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'icon', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_update_category_why_not_find()
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
        $request->assertJson(['errors' => ['main' => 'Category not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_category_with_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('category.jpg');

        $payload = [
            'description' => 'Category description Updated',
            'icon' => $file
        ];

        $request = $this->putJson(route('categories.update', ['category' => $category->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'icon', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/categories/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_category_without_file()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $category = Category::factory()->create();

        $payload = [
            'name' => 'Category Test Updated',
            'description' => 'Category description Updated'
        ];

        $request = $this->putJson(route('categories.update', ['category' => $category->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'icon', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_delete_category()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->delete(route('categories.destroy', ['category' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Category not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_delete_category()
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
