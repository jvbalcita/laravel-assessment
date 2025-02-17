<?php

namespace App\Http\Requests;

use App\Rules\NotSelectOne;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'user_name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->user)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'photo' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255', new NotSelectOne()],
        ];
    }
}
