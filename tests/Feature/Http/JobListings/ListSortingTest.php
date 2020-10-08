<?php

namespace Tests\Feature\Http\JobListings;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListSortingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_sort_by_title_in_ascending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=title');

        $response->assertStatus(200);

        $listings
            ->sortBy('title')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_title_in_descending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=-title');

        $response->assertStatus(200);

        $listings
            ->sortByDesc('title')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_description_in_ascending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=description');

        $response->assertStatus(200);

        $listings
            ->sortBy('description')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_description_in_descending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=-description');

        $response->assertStatus(200);

        $listings
            ->sortByDesc('description')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_company_name_in_ascending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=company_name');

        $response->assertStatus(200);

        $listings
            ->sortBy('company.name')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_company_name_in_descending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=-company_name');

        $response->assertStatus(200);

        $listings
            ->sortByDesc('company.name')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_published_at_in_ascending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=published_at');

        $response->assertStatus(200);

        $listings
            ->sortBy('published_at')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }

    /** @test */
    public function it_can_sort_by_published_at_in_descending_order()
    {
        $listings = Listing::factory(5)->create();

        $response = $this->getJson('/api/listings?sort=-published_at');

        $response->assertStatus(200);

        $listings
            ->sortByDesc('published_at')
            ->values()
            ->each(fn($listing, $index) =>
                $response->assertJsonPath("listings.data.{$index}.id", $listing->id)
            );
    }
}
