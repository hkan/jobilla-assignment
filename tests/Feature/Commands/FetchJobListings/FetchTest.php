<?php

namespace Tests\Feature\Commands\FetchJobListings;

use App\Services\RemoteJobListingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FetchTest extends TestCase
{
    use RefreshDatabase, Traits\WithFakeRemoteJobs, WithFaker;

    /** @test */
    public function it_calls_service_and_outputs_job_count_for_dry_run()
    {
        $this->mock(RemoteJobListingsService::class,
            fn($mock) => $mock->shouldReceive('fetch')
                ->once()
                ->andReturn($this->getFakeRemoteJobData())
        );

        $this->artisan('jobs:fetch-remote --dry-run')
            ->expectsOutput('Fetched 5 jobs.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_calls_service_and_dumps_datatable_for_verbose_dry_run()
    {
        $data = $this->getFakeRemoteJobData();

        $this->mock(RemoteJobListingsService::class,
            fn($mock) => $mock->shouldReceive('fetch')
                ->once()
                ->andReturn($data)
        );

        $this->artisan('jobs:fetch-remote --dry-run -v')
            ->expectsTable(
                ['Job title', 'Description', 'Creation Time', 'Company'],
                $data->map(fn($job) => [
                    $job->otsikko,
                    $job->kuvausteksti,
                    $job->ilmoituspaivamaara,
                    $job->tyonantajanNimi,
                ])
            )
            ->assertExitCode(0);
    }
}
