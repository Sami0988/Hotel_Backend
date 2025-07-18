<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{

    // List all bookings
  public function index()
{
    $user = Auth::user();

    if (!in_array($user->role, ['admin', 'employee'])) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Your actual logic here
    return Booking::with(['user', 'room'])->get();
}


    // Store a new booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'in:pending,confirmed,cancelled,completed',
            'special_requests' => 'nullable|string',
        ]);

        $booking = Booking::create($validated);
        return response()->json($booking, 201);
    }

    // Show a single booking
    public function show($id)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);
        return response()->json($booking);
    }

    // Update an existing booking
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'check_in' => 'sometimes|date|after_or_equal:today',
            'check_out' => 'sometimes|date|after:check_in',
            'guests_count' => 'sometimes|integer|min:1',
            'total_price' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
            'special_requests' => 'nullable|string',
        ]);

        $booking->update($validated);
        return response()->json($booking);
    }

    // Delete a booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
