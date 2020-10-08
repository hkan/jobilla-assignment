<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Listing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => fn() => Company::factory()->create()->id,
            'title' => $this->faker->words(rand(2, 5), true),
            'description' => $this->faker->paragraphs(rand(1, 3), true),
            'published_at' => now()
                ->subMinutes($this->faker->numberBetween(30, 60 * 24 * 10))
                ->milliseconds(0),
        ];
    }
}
