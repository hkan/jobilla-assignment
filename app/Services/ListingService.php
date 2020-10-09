<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ListingService
{
    public function getLatestPaginated(
        ?string $search,
        array $sort
    ): LengthAwarePaginator
    {
        return Listing::query()
            ->when(
                $sort['column'] == 'company_name',
                fn(Builder $query) => $this->sortByCompanyName($query, $sort['order']),
                fn(Builder $query) => $query->orderBy($sort['column'], $sort['order'])
            )
            ->when(
                $search !== null,
                fn(Builder $query) => $this->filterListings($query, $search)
            )
            ->paginate();
    }

    public function getSortTupleFromQueryString(string $queryString): array
    {
        $order = Str::startsWith($queryString, '-') ? 'desc' : 'asc';
        $column = substr($queryString, $order === 'desc' ? 1 : 0);

        return [
            'column' => $column,
            'order' => $order,
        ];
    }

    protected function sortByCompanyName(Builder $query, string $order): void
    {
        $query
            ->orderBy('companies.name', $order)
            ->select('listings.*')
            ->leftJoin('companies', 'companies.id', '=', 'listings.company_id');
    }

    protected function filterListings(Builder $searchQuery, string $search): void
    {
        $searchQuery
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%")
            ->orWhere(fn(Builder $subquery) =>
                $subquery->whereHas('company', fn(Builder $relationQuery) =>
                    $relationQuery->where('name', 'LIKE', "%{$search}%")
                )
            );
    }
}
