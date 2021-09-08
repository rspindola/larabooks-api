<?php

namespace Database\Factories;

use App\Models\{Book, Category, Company};
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->words(3, true),
            'cover' => $this->faker->image(public_path('images/books'), 640, 480, null, false),
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'about' => $this->faker->paragraphs(3, true),
            'gender' => $this->faker->word(),
            'pages' => $this->faker->numberBetween(0, 300),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'status' => $this->faker->boolean(),
            'published_at' => $this->faker->date(),
            'slug' => $this->faker->slug()
        ];
    }
}
