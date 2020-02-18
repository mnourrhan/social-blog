<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email'  => 'required|regex:/[^@]+@[^\.]+\..+/|unique:users',
            'password'  => 'required',
            'birth_date'  => 'required',
            'image' => 'mimes:jpeg,png',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.regex' => 'Invalid email! Please enter a valid one',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!',
            'email.unique' => 'The email you have entered is already registered!',
            'birth_date.required' => 'Birth date is required!',
            'image.mimes' => 'The allowed image types are jpg and png only!'
        ];
    }
}