<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric', 'gt:0'],
            'description' => ['required', 'string', 'max:191'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'user_id' => ['required', 'exists:' . User::class . ',id'],
        ];
    }
}
