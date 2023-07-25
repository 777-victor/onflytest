<?php

namespace App\Http\Requests;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Expense $expense): bool
    {
        // if ($this->user()->cannot('update', $expense)) {
        //     abort(403);
        // }
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
            'id' => ['required', 'numeric', 'exists:' . Expense::class . ',id'],
            'value' => ['required', 'numeric', 'gt:0'],
            'description' => ['required', 'string', 'max:191'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'user_id' => ['required', 'exists:' . User::class . ',id'],
        ];
    }
}
