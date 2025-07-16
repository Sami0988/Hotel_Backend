<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

   public function rules(): array
{
    $roomId = $this->route('room')->id;

    if ($this->isMethod('put')) {
        // Full update: all fields are required
        return [
            'room_number' => 'required|unique:rooms,room_number,' . $roomId,
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'status' => 'required|string',
        ];
    }

    // PATCH (partial update): only validate if present
    return [
        'room_number' => 'sometimes|required|unique:rooms,room_number,' . $roomId,
        'type' => 'sometimes|required|string',
        'capacity' => 'sometimes|required|integer',
        'price_per_night' => 'sometimes|required|numeric',
        'status' => 'sometimes|required|string',
    ];
}

}

