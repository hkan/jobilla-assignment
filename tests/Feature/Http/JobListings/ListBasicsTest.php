<?php

namespace Tests\Feature\Http\JobListings;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListBasicsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_listings_in_a_paginated_fashion()
    {
        $listings = Listing::factory(20)->create();

        $this->getJson('/api/listings')
            ->assertStatus(200)
            ->assertJson([
                'listings' => [
                    'total' => 20,
                ],
            ], true);
    }

    /** @test */
    public function it_shows_the_latest_listing_at_first()
    {
        $listings = Listing::factory(20)->create();

        $this->getJson('/api/listings')
            ->assertStatus(200)
            ->assertJson([
                'listings' => [
                    'data' => [
                        [
                            'id' => $listings->sortByDesc('published_at')->first()->id,
                        ],
                    ],
                ],
            ], true);
    }
}
