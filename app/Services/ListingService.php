<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ListingService
{
    public function getLatestPaginated(?string $search): LengthAwarePaginator
    {
        return Listing::orderBy('published_at', 'desc')
            ->when($search !== null, fn(Builder $searchQuery) =>
                $searchQuery
                    ->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere(fn(Builder $subquery) =>
                        $subquery->whereHas('company', fn(Builder $relationQuery) =>
                            $relationQuery->where('name', 'LIKE', "%{$search}%")
                        )
                    )
            )
            ->paginate();
    }
}
