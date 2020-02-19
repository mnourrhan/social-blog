<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TweetStoreRequest extends FormRequest
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
            'content'  => 'required|max:140',
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
            'content.required' => 'Tweet content is required!',
            'content.max' => 'Tweet length limit exceed! The max limit is 140 character.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(jsend_fail($validator->errors()->all()));
    }
}