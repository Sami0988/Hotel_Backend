<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users to create a table
        return true;
    }

   public function rules()
{
    return [
        'table_number' => 'required|string|unique:tables,table_number,' . ($this->table ? $this->table->id : 'NULL'),
        'capacity' => 'required|integer|min:1',
        'status' => 'required|string|in:available,reserved,occupied,maintenance',
        'chair_type' => 'required|string|in:standard,barstool,booth,armchair,comfortable',
        'location' => 'required|string|max:200',
        'min_guests' => 'required|integer|min:1|lte:capacity',
        'max_guests' => 'required|integer|gte:min_guests|max:20',
        'is_smoking_allowed' => 'sometimes|boolean',
        'price_per_hour' => 'nullable|numeric|min:0|max:1000',
        'description' => 'nullable|string|max:500'
    ];
}
}
