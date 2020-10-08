<?php

namespace App\Http\Controllers;

use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingController extends Controller
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

        return [
            'listings' => $listings,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}
