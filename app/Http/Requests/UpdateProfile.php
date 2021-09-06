<?php

namespace App\Http\Requests;

use App\Helpers\ApiErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends FormRequest
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
        $todayDate = date('Y-m-d');

        return [
            'email' => ['nullable', 'email', 'unique:users,email,' . Auth::user()['id'], 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'regex:/^(\+63)\d{10}$/'],
            'sex' => ['nullable', 'string', 'in:male,female'],
            'birthday' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:' . $todayDate],
            'home_address' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
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
            'mobile_number.regex' => 'The mobile number should follow this format: +63XXXXXXXXXX.',
            'sex.in' => 'Valid sex values are `male` and `female`'
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
