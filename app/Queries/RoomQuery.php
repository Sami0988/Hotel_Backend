<?php

namespace App\Queries;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomQuery
{
    public static function apply(Request $request)
    {
        $query = Room::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('type', 'ILIKE', "%{$search}%")
                  ->orWhere('status', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', $request->input('capacity'));
        }

        $perPage = $request->input('per_page', 10);

        return $query->paginate($perPage);
    }
}
