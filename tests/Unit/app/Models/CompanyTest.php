<?php
namespace Tests\Unit;

use App\Models\Book;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function company_has_many_books()
    {
        $company = Company::factory()
                ->has(Book::factory(4))
                ->create();

        // Method 2:
        $this->assertEquals(4, $company->books->count());
    }
}
