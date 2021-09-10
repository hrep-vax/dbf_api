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
    public static string $UNKNOWN_ROUTE_CODE = 'UNKNOWN_ROUTE_ERROR';

    /***
     * Creates and returns a custom API error message
     * @param string $message
     * @param array|null $errors
     * @param int $statusCode
     * @param String $errorCode
     * @return HttpResponseException
     */
    public static function createErrorResponse(string $message, ?array $errors, int $statusCode, string $errorCode = 'SERVER_ERROR')
    {
        $response = [
            'errorCode' => $errorCode,
            'message' => $message,
            'errors' => $errors
        ];

        return new HttpResponseException(response()->json($response)->setStatusCode($statusCode));
    }
}