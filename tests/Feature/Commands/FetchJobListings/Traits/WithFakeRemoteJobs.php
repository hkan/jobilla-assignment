<?php

namespace Tests\Feature\Commands\FetchJobListings\Traits;

use Illuminate\Support\Collection;

trait WithFakeRemoteJobs
{
    /*
     * Generates fake data to mock remote API.
     */
    protected function getFakeRemoteJobData(int $size = 5): Collection
    {
        return collect(range(1, 5))->map(fn() => (object) [

            // A title consisting of two to five words.
            'otsikko' => $this->faker->words(rand(2, 5), true),

            // A block of one to five paragraphs.
            'kuvausteksti' => $this->faker->paragraphs(rand(1, 5), true),

            // Mark it as created between half an hour prior and 10 days prior.
            'ilmoituspaivamaara' => now()
                ->subMinutes($this->faker->numberBetween(30, 60 * 24 * 10))
                ->milliseconds(0),

            // Random company name.
            'tyonantajanNimi' => $this->faker->company,

        ]);
    }
}
