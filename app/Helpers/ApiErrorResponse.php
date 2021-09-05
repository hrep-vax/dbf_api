<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\MessageBag;
use Ramsey\Uuid\Type\Integer;

class ApiErrorResponse
{
    public static string $VALIDATION_ERROR_CODE = 'VALIDATION_ERROR';
    public static string $RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';

    /***
     * Creates and returns a custom API error message
     * @param String $message
     * @param MessageBag $msgBag
     * @param int $statusCode
     * @param String $errorCode
     * @return HttpResponseException
     */
    public static function createErrorResponse(String $message, MessageBag $msgBag, int $statusCode ,String $errorCode = 'SERVER_ERROR')
    {
        $response = [
            'errorCode' => $errorCode,
            'message' => $message,
            'errors' => $msgBag
        ];

        return new HttpResponseException(response()->json($response)->setStatusCode($statusCode));
    }
}