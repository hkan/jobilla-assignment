<?php

namespace App\Services;

use App\Models\Listing;

class ListingService
{
    public function getLatestPaginated()
    {
        return Listing::orderBy('published_at', 'desc')->paginate();
    }
}
