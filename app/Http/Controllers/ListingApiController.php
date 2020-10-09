<?php

namespace App\Http\Controllers;

use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $listingService = app(ListingService::class);

        $listings = $listingService->getLatestPaginated(
            $request->get('search'),
            $listingService->getSortTupleFromQueryString(
                $request->get('sort', '-published_at')
            )
        );

        // Company information will be expected too.
        $listings->load('company');

        return [
            'listings' => $listings,
        ];
    }
}
