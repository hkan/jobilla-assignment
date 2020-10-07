<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Listing;
use App\Models\RemoteJobListing;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class RemoteJobListingsService
{
    /*
     * Fetches the JSON data from external API and returns it as a collection.
     */
    public function fetch(): Collection
    {
        $response = $this->performFetchRequest(app(Client::class));

        return collect($response->response->docs);
    }

    /*
     * Save a remote job listing data to database.
     */
    public function persistRemoteListingData(RemoteJobListing $data): Listing
    {
        // If company name exists in the data, we need an ID for it.
        if ($data->company_name !== null) {
            $company = Company::firstOrCreate([
                'name' => $data->company_name
            ]);
        }

        return Listing::create([
            'company_id' => optional($company)->id,
            'title' => $data->title,
            'description' => $data->description,
            'published_at' => $data->published_at,
        ]);
    }

    /*
     * Connect to the external API and get data.
     */
    protected function performFetchRequest(Client $client): object
    {
        // Make the HTTP request.
        $response = $client->request('GET', config('external_api.url'));

        // Parse and the response body.
        return json_decode((string) $response->getBody());
    }
}
