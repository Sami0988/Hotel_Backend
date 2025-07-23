<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\TableReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TableReservationController extends Controller
{
    /**
     * List all table reservations
     */
    public function index()
    {
        try {
            return TableReservation::with(['table'])
                ->orderBy('reservation_time', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Fetch failed: '.$e->getMessage());
            return response()->json(['message' => 'Failed to retrieve reservations',$e], 500);
        }
    }

    /**
     * Create new table reservation
     */
 public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'table_id' => 'required|exists:tables,id',
            'reservation_time' => 'required|date|after_or_equal:now',
            'guests_count' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        

        $table = Table::findOrFail($validated['table_id']);

        if (!$this->checkTableAvailability($table->id, $validated['reservation_time'])) {
            return response()->json(['message' => 'Table not available'], 422);
        }



        if ($validated['guests_count'] > $table->capacity) {
            return response()->json(['message' => 'Too many guests for this table u cnat book it!'], 422);
        }

        $reservation = TableReservation::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'table_id' => $validated['table_id'],
            'reservation_time' => $validated['reservation_time'],
            'guests_count' => $validated['guests_count'],
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending'
        ]);

        return response()->json($reservation, 201);

    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
       
        return response()->json(['message' => 'Failed to create reservation',$e], 500);
    }
}



    /**
     * Show single reservation
     */
  public function show($id)
{
    $reservation = TableReservation::with(['table'])->find($id);

    if (!$reservation) {
        return response()->json([
            'success' => false,
            'message' => 'Reservation not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $reservation
    ]);
}


    /**
     * Update reservation
     */
    public function update(Request $request, $id)
    {
        try {
            $reservation = TableReservation::findOrFail($id);

            $validated = $request->validate([
                'table_id' => 'sometimes|exists:tables,id',
                'reservation_time' => 'sometimes|date|after_or_equal:now',
                'guests_count' => 'sometimes|integer|min:1',
                'special_requests' => 'nullable|string|max:500',
                'status' => 'sometimes|string|in:pending,confirmed,cancelled'
            ]);

            if (isset($validated['table_id']) && isset($validated['reservation_time'])) {
                if (!$this->checkTableAvailability($validated['table_id'], $validated['reservation_time'], $reservation->id)) {
                    return response()->json(['message' => 'Table not available at selected time'], 422);
                }
            }

            $reservation->update($validated);
            return response()->json($reservation);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed'], 500);
        }
    }

    /**
     * Delete reservation
     */
    public function destroy($id)
    {
        try {
            $reservation = TableReservation::findOrFail($id);
            $reservation->delete();
            return response()->json(['message' => 'Reservation deleted']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Deletion failed'], 500);
        }
    }

    /**
     * Check table availability
     */
    private function checkTableAvailability($tableId, $reservationTime, $excludeReservationId = null)
    {
        $startTime = Carbon::parse($reservationTime)->subHours(2);
        $endTime = Carbon::parse($reservationTime)->addHours(2);

        $query = TableReservation::where('table_id', $tableId)
            ->where('reservation_time', '>', $startTime)
            ->where('reservation_time', '<', $endTime)
            ->where('status', '!=', 'cancelled');

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return !$query->exists();
    }

    /**
     * Get available tables
     */
    public function availableTables(Request $request)
    {
        try {
            $validated = $request->validate([
                'reservation_time' => 'required|date|after_or_equal:now',
                'guests_count' => 'required|integer|min:1'
            ]);

            $tables = Table::where('capacity', '>=', $validated['guests_count'])
                ->where('status', 'available')
                ->get();

            $availableTables = $tables->filter(function ($table) use ($validated) {
                return $this->checkTableAvailability($table->id, $validated['reservation_time']);
            });

            return response()->json($availableTables);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch tables'], 500);
        }
    }
}
