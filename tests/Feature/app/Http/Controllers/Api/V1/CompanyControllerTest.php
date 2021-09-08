<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\{DatabaseMigrations, RefreshDatabase};
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
    public function testShouldGetCompanys()
    {
        $request = $this->getJson(route('companies.index'));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCanAddCompany()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();
        $payload = [
            'name' => $company->name,
            'description' => $company->description,
            'about' => $company->about,
            'logo' => $company->logo,
            'website' => $company->website,
        ];

        $request = $this->postJson(route('companies.store'), $payload);
        $request->assertStatus(201);
    }


    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantFindACompany()
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
    public function testShouldCanFindACompany()
    {

        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();

        $request = $this->getJson(route('companies.show', ['company' => $company]));
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantUpdateCompanyWhyNotFind()
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
    public function testShouldCanUpdateACompany()
    {
        Passport::actingAs(
            User::factory()->create()
        );

        $company = Company::factory()->create();

        $payload = [
            'name' => 'Company Test Updated',
            'description' => 'Company description Updated'
        ];

        $request = $this->putJson(route('companies.update', ['company' => $company->id]), $payload);
        $request->assertStatus(200);
    }

    /**
     * Teste enviando dados corretos.
     *
     * @return void
     */
    public function testShouldCantDeleteCompany()
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
    public function testShouldCanDeleteCompany()
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
