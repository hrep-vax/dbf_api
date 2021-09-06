<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class FileStorageController extends Controller
{
    /**
     * @throws FileNotFoundException
     */
    public function showProfilePicture()
    {
        if (!Auth::user()->profile_picture_url) {
            return throw ApiErrorResponse::createErrorResponse('User has no associated profile picture', NULL, 404, ApiErrorResponse::$RESOURCE_NOT_FOUND_CODE);
        }

        $fileName = pathinfo(Auth::user()->profile_picture_url)['basename'];

        $path = storage_path('app/public/uploads/profile_pictures/' . $fileName);

        if (!File::exists($path)) {
            return throw ApiErrorResponse::createErrorResponse('Cannot resolve profile picture filepath', NULL, 404, ApiErrorResponse::$RESOURCE_NOT_FOUND_CODE);
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
