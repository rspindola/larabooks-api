<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\{DatabaseMigrations, RefreshDatabase};
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
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
    public function test_should_get_companies()
    {
        $request = $this->getJson(route('companies.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_company_with_logo()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        Storage::fake('public');
        $file = UploadedFile::fake()->image('company.jpg');

        $payload = [
            'name' => 'Test name',
            'description' => 'Test description',
            'about' => 'Test about',
            'logo' => $file,
            'website' => 'http://test.com',
        ];

        $request = $this->postJson(route('companies.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['name', 'description', 'about', 'logo', 'website', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/companies/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_add_company_without_logo()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payload = [
            'name' => 'Test name',
            'description' => 'Test description',
            'about' => 'Test about',
            'website' => 'http://test.com',
        ];

        $request = $this->postJson(route('companies.store'), $payload);
        $request->assertStatus(201);
        $request->assertJsonStructure(['name', 'description', 'about', 'logo', 'website', 'slug', 'dateForHumans', 'created_at']);
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_find_company()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->getJson(route('companies.show', ['company' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Company not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_find_company()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();

        $request = $this->getJson(route('companies.show', ['company' => $company]));
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'about', 'logo', 'website', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_update_company_mot_find()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;
        $payload = [
            'name' => 'Company Test Updated',
            'description' => 'Company description Updated'
        ];

        $request = $this->putJson(route('companies.update', ['company' => $payloadID]), $payload);
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Company not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_company_with_logo()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('company.jpg');


        $payload = [
            'description' => 'Company description Updated',
            'logo' => $file
        ];

        $request = $this->putJson(route('companies.update', ['company' => $company->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'about', 'logo', 'website', 'slug', 'dateForHumans', 'created_at']);
        Storage::disk('public')->assertExists('images/companies/' . $file->hashName());
        Storage::disk('public')->assertMissing('missing.jpg');
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_update_company_without_logo()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();

        $payload = [
            'description' => 'Company description Updated'
        ];

        $request = $this->putJson(route('companies.update', ['company' => $company->id]), $payload);
        $request->assertStatus(200);
        $request->assertJsonStructure(['name', 'description', 'about', 'logo', 'website', 'slug', 'dateForHumans', 'created_at']);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_cant_delete_company()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $payloadID = 1;

        $request = $this->delete(route('companies.destroy', ['company' => $payloadID]));
        $request->assertStatus(404);
        $request->assertJson(['errors' => ['main' => 'Company not found']]);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function test_should_can_delete_company()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();

        $request = $this->delete(route('companies.destroy', ['company' => $company->id]));
        $request->assertStatus(200);
        $request->assertJson(['success' => ['main' => 'Company deleted']]);
    }
}
