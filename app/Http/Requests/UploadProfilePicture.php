<?php

namespace App\Http\Requests;

use App\Helpers\ApiErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadProfilePicture extends FormRequest
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
            'image' => ['required', 'mimes:jpeg,png', 'max:5120']
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
            'image.mimes' => 'The uploaded file must a JPEG or PNG',
            'image.max' => 'The file must not exceed 5MB'
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
