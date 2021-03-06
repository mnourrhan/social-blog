<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
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
            'email'  => 'required|regex:/[^@]+@[^\.]+\..+/',
            'password'  => 'required',
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
            'password.required' => 'Password is required!',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(jsend_fail($validator->errors()->all()));
    }
}