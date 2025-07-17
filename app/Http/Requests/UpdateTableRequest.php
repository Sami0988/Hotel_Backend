<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users to update a table
        return true;
    }

    public function rules()
{
    $tableId = $this->route('table')->id ?? null;

    return [
        'table_number' => 'sometimes|required|string|unique:tables,table_number,' . $tableId,
        'capacity' => 'sometimes|required|integer|min:1',
        'seats' => 'sometimes|required|integer|min:1|lte:capacity',
        'status' => 'sometimes|required|string|in:available,reserved,maintenance',
        'chair_type' => 'sometimes|required|string|in:standard,barstool,booth,armchair',
        'location' => 'sometimes|required|string|max:50',
        'min_guests' => 'sometimes|required|integer|min:1',
        'max_guests' => 'sometimes|required|integer|gte:min_guests|max:20',
        'is_smoking_allowed' => 'sometimes|boolean',
        'price_per_hour' => 'sometimes|nullable|numeric|min:0|max:1000',
        'description' => 'sometimes|nullable|string|max:500',
    ];
}

}
