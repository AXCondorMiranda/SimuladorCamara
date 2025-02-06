<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'test_type_id' => 'required|exists:test_types,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'is_practice' => 'nullable|boolean',
        ];
    }
}
