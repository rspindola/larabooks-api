<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->domainWord(),
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'about' => $this->faker->paragraphs(3, true),
            'logo' => $this->faker->image(public_path('images/companies'), 640, 480, null, false),
            'website' => $this->faker->url(),
            'slug' => $this->faker->slug
        ];
    }
}
