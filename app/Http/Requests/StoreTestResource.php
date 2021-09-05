<?php

namespace App\Http\Requests;

use App\Exceptions\Handler;
use App\Helpers\ApiErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\MessageBag;

class StoreTestResource extends FormRequest
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
            'name' => ['required', 'max:255', 'unique:test_resources,name'],
            'description' => ['required', 'max:255']
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
            'name.required' => 'The `name` field is required.',
            'name.unique' => 'The `name` field is already taken.',
            'name.max' => 'The `name` field must not exceed 255 characters.',
            'description.required' => 'The `description` field is required.',
            'description.max' => 'The `description` field must not exceed 255 characters.',
        ];
    }

    /**
     * Custom validation JSON response
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errorMsg = "A validation error has occurred.";
        $msgBag = $validator->getMessageBag();

        throw ApiErrorResponse::createErrorResponse($errorMsg, $msgBag, 422, ApiErrorResponse::$VALIDATION_ERROR_CODE);
    }
}
