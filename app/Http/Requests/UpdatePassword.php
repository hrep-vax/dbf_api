<?php

namespace App\Http\Requests;

use App\Helpers\ApiErrorResponse;
use App\Rules\SameAsCurrentPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePassword extends FormRequest
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
            'old_password' => ['required', 'string', new SameAsCurrentPassword()],
            'password' => ['required', 'string', 'min:6', 'max:255', 'confirmed']
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
            'old_password.is_current_password' => 'The old password field is incorrect.',
        ];
    }

    /**
     * Custom validation JSON response
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errorDesc = "A validation error has occurred.";
        $message = $validator->getMessageBag()->toArray();

        throw ApiErrorResponse::createErrorResponse($errorDesc, $message, 422, ApiErrorResponse::$VALIDATION_ERROR_CODE);
    }
}
