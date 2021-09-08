<?php
namespace Tests\Unit;

use App\Models\Book;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function book_belongs_to_category()
    {
        $book = Book::factory()
                ->for(Category::factory())
                ->create();

        $this->assertEquals(1, $book->category->count());
    }

    /** @test */
    public function book_belongs_to_company()
    {
        $book = Book::factory()
                ->for(Company::factory())
                ->create();

        $this->assertEquals(1, $book->company->count());
    }
}
