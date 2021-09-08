<?php
namespace Tests\Unit;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function category_has_many_books()
    {
        $category = Category::factory()
                ->has(Book::factory(4))
                ->create();

        // Method 2:
        $this->assertEquals(4, $category->books->count());
    }
}
