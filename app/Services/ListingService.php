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
        return Listing::orderBy($sort['column'], $sort['order'])
            ->when($sort['column'] == 'company_name', fn(Builder $query) =>
                $query->orderBy('companies.name', $sort['order'])
                    ->leftJoin('companies', 'companies.id', '=', 'listings.company_id')
            )
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

    public function getSortTupleFromQueryString(string $queryString): array
    {
        $order = substr($queryString, 0, 1) === '-' ? 'desc' : 'asc';
        $column = substr($queryString, $order === 'desc' ? 1 : 0);

        return [
            'column' => $column,
            'order' => $order,
        ];
    }
}
