<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with('bookable')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bookable_type' => 'required|string',
            'bookable_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'guest_name' => 'nullable|string',
            'guest_email' => 'nullable|email',
            'guest_phone' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $booking = Booking::create($data);

        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        $booking->load('bookable');
        return $booking;
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'guest_name' => 'sometimes|string',
            'guest_email' => 'sometimes|email',
            'guest_phone' => 'sometimes|string',
            // you can also allow updating bookable_type and bookable_id if you want
        ]);
        $booking->update($data);

        return response()->json($booking);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }
}
