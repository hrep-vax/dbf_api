<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiErrorResponse
{
    public static string $VALIDATION_ERROR_CODE = 'VALIDATION_ERROR';
    public static string $RESOURCE_NOT_FOUND_CODE = 'RESOURCE_NOT_FOUND_ERROR';
    public static string $INVALID_CREDENTIALS_CODE = 'INVALID_CREDENTIALS_ERROR';
    public static string $SMTP_ERROR_CODE = 'SMTP_ERROR';
    public static string $UNAUTHENTICATED_CODE = 'UNAUTHENTICATED_ERROR';
    public static string $UNAUTHORIZED_CODE = 'UNAUTHORIZED_ERROR';

    /***
     * Creates and returns a custom API error message
     * @param String $description
     * @param array|null $message
     * @param int $statusCode
     * @param String $errorCode
     * @return HttpResponseException
     */
    public static function createErrorResponse(String $description, ?Array $message, int $statusCode ,String $errorCode = 'SERVER_ERROR')
    {
        $response = [
            'errorCode' => $errorCode,
            'message' => $description,
            'errors' => $message
        ];

        return new HttpResponseException(response()->json($response)->setStatusCode($statusCode));
    }
}