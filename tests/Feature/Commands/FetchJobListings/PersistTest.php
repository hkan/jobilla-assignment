<?php

namespace Tests\Feature\Commands\FetchJobListings;

use App\Models\Listing;
use App\Services\RemoteJobListingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PersistTest extends TestCase
{
    use RefreshDatabase, Traits\WithFakeRemoteJobs, WithFaker;

    /** @test */
    public function it_can_save_all_data_to_database()
    {
        $data = $this->getFakeRemoteJobData();

        $this->partialMock(RemoteJobListingsService::class,
            fn($mock) => $mock->shouldReceive('fetch')
                ->once()
                ->andReturn($data)
        );

        $this->artisan('jobs:fetch-remote')
            ->expectsOutput('Fetched 5 jobs and saved them to database.')
            ->assertExitCode(0);

        $this->assertEquals(5, Listing::count());

        $firstListing = Listing::orderBy('id', 'asc')->first();

        $this->assertEquals($data[0]->otsikko, $firstListing->title);
        $this->assertEquals($data[0]->kuvausteksti, $firstListing->description);
        $this->assertEquals($data[0]->ilmoituspaivamaara, $firstListing->published_at);
        $this->assertEquals($data[0]->tyonantajanNimi, $firstListing->company->name);
    }
}
