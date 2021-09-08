<?php

namespace App\Exceptions;

use App\Helpers\ApiErrorResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\MockObject\Api;
use PHPUnit\Util\Exception;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Override Spatie's UnauthorizedException message
        $this->renderable(function (UnauthorizedException $e, $request) {
            return response()->json([
                'message'  => $e->getMessage(),
                'errorCode' => ApiErrorResponse::$UNAUTHORIZED_CODE,
                'errors' => NULL
            ], 403);
        });

        $this->reportable(function (Throwable $e) {});
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'errorCode' => ApiErrorResponse::$VALIDATION_ERROR_CODE,
            'message' => 'A validation error has occurred.',
            'errors' => $this->transformErrors($exception),
        ], $exception->status);
    }

    // Transform the error messages,
    private function transformErrors(ValidationException $exception)
    {
        $errors = (object) [];
        foreach ($exception->errors() as $field => $message) {
            $errors->{$field} = $message;
        }
        return $errors;
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(
                ['message' => $exception->getMessage(), 'errorCode' => ApiErrorResponse::$UNAUTHENTICATED_CODE, 'errors' => NULL],
                401)

            // No implementation for APIs
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
