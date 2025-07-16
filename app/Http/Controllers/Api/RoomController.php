<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Queries\RoomQuery;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET /api/rooms
   public function index(Request $request)
{
    $rooms = RoomQuery::apply($request);
    return response()->json($rooms);
}

    // POST /api/rooms
    public function store(StoreRoomRequest $request)
{
    $room = Room::create($request->validated());
    return response()->json($room, 201);
}

   // PUT /api/rooms/{room}
   public function update(UpdateRoomRequest $request, Room $room)
{
    $room->update($request->validated());
    return response()->json($room);
}


    // GET /api/rooms/{room}
    public function show(Room $room)
    {
        return response()->json($room);
    }

 


    // DELETE /api/rooms/{room}
    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json(null, 204);
    }
}
