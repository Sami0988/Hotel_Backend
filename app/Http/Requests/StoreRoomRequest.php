<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules()
    {
        return [
            'room_number' => 'required|unique:rooms,room_number',
            'type' => 'required|string',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'status' => 'required|string',
        ];
    }
}

