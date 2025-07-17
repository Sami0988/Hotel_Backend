<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Queries\TableQuery;
use Illuminate\Http\Request;



class TableController extends Controller
{
public function index(Request $request)
{
    $results = Table::query()
        ->when($request->anyFilled(['search', 'capacity', 'status', 'location']),
            fn($q) => (new TableQuery)->apply($q, $request))
        ->paginate($request->input('per_page', 10));

    return response()->json([
        'data' => $results->items(),
        'links' => [
            'first' => $results->url(1),
            'last' => $results->url($results->lastPage()),
            'prev' => $results->previousPageUrl(),
            'next' => $results->nextPageUrl(),
        ],
        'meta' => [
            'current_page' => $results->currentPage(),
            'from' => $results->firstItem(),
            'to' => $results->lastItem(),
            'per_page' => $results->perPage(),
            'total' => $results->total(),
        ]
    ]);
}

    public function show(Table $table)
{
    return response()->json([
        'data' => $table
    ]);
}


  // In your TableController

public function store(StoreTableRequest $request)
{
    $table = Table::create($request->validated());
    return response()->json($table, 201);
}

public function update(UpdateTableRequest $request, Table $table)
{
    $table->update($request->validated());
    return response()->json($table);
}

    public function destroy(Table $table)
    {
        $table->delete();

        

            return response()->json([
        'message' => 'Table deleted successfully',
    ]);

    }
}
