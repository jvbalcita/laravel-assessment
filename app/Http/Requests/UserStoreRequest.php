<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NotSelectOne;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prefix_name' => ['required', 'string', 'max:255', new NotSelectOne()],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix_name' => ['nullable', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'email:rfc,dns,filter', 'string', 'max:255', 'unique:'.User::class],
            'type' => ['required', 'string', 'max:255', new NotSelectOne()],
        ];
    }
}
