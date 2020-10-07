<?php

namespace App\Console\Commands;

use App\Models\RemoteJobListing;
use App\Services\RemoteJobListingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FetchJobListings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:fetch-remote
        {--dry-run : Just fetch the data, and do not store them}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gathers the job listings from the external API and stores them in the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $service = app(RemoteJobListingsService::class);

        $jobs = $service->fetch();

        if ($this->option('dry-run')) {
            return $this->outputResultsForJobs($jobs);
        }

        $jobs
            ->map(fn($data) => RemoteJobListing::fromRemoteDataObject($data))
            ->each(fn($model) => $service->persistRemoteListingData($model));

        $this->info(sprintf('Fetched %d jobs and saved them to database.', $jobs->count()));

        return 0;
    }

    /*
     * Outputs information about the fetched jobs.
     */
    protected function outputResultsForJobs(Collection $jobs): int
    {
        if ($this->option('verbose')) {
            $this->table(
                ['Job title', 'Description', 'Creation Time', 'Company'],
                $jobs->map(fn($job) => [
                    $job->otsikko,
                    $job->kuvausteksti,
                    $job->ilmoituspaivamaara,
                    $job->tyonantajanNimi,
                ])
            );
        } else {
            $this->info(sprintf('Fetched %d jobs.', count($jobs)));
        }

        return 0;
    }
}
