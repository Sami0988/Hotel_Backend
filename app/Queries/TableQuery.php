<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TableQuery
{
    /**
     * Apply search and filter conditions to the query builder.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function apply(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('search'), fn($q) => $this->applySearch($q, $request->input('search')))
            ->when($request->filled('capacity'), fn($q) => $this->filterByCapacity($q, $request->input('capacity')))
            ->when($request->filled('status'), fn($q) => $this->filterByStatus($q, $request->input('status')));
    }

    protected function applySearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('table_number', 'ILIKE', "%{$searchTerm}%")
              ->orWhere('description', 'ILIKE', "%{$searchTerm}%");
        });
    }

    protected function filterByCapacity(Builder $query, int $capacity): Builder
    {
        return $query->where('capacity', '>=', $capacity);
    }

    protected function filterByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

   
}