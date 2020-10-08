<?php

namespace Tests\Feature\Http\JobListings;

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_searches_in_job_title()
    {
        $this->withoutExceptionHandling();

        $theOneToMatch = Listing::factory()->create([
            'title' => 'long title to make sure nothing else matches',
        ]);

        Listing::factory()->create([
            'title' => 'some other title that I am sure will not match',
        ]);

        $this->getJson('/api/listings?search=long+title+to+make+sure')
            ->assertStatus(200)
            ->assertJson([
                'listings' => [
                    'total' => 1,
                    'data' => [
                        ['id' => $theOneToMatch->id],
                    ],
                ],
            ], true);
    }

    /** @test */
    public function it_searches_in_job_description()
    {
        $this->withoutExceptionHandling();

        $theOneToMatch = Listing::factory()->create([
            'description' => 'long description to make sure nothing else matches',
        ]);

        Listing::factory()->create([
            'description' => 'some other description that I am sure will not match',
        ]);

        $this->getJson('/api/listings?search=long+description+to+make+sure')
            ->assertStatus(200)
            ->assertJson([
                'listings' => [
                    'total' => 1,
                    'data' => [
                        ['id' => $theOneToMatch->id],
                    ],
                ],
            ], true);
    }

    /** @test */
    public function it_searches_in_company_name()
    {
        $this->withoutExceptionHandling();

        $theOneToMatch = Listing::factory()->create([
            'company_id' => Company::factory()->create([
                'name' => 'long company name to make sure nothing else matches'
            ])->id,
        ]);

        Listing::factory()->create([
            'company_id' => Company::factory()->create([
                'name' => 'some other name that I am sure will not match'
            ])->id,
        ]);

        $this->getJson('/api/listings?search=long+company+name+to+make+sure')
            ->assertStatus(200)
            ->assertJson([
                'listings' => [
                    'total' => 1,
                    'data' => [
                        ['id' => $theOneToMatch->id],
                    ],
                ],
            ], true);
    }
}
