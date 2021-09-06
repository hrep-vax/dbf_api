<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use App\Http\Requests\LoginUser;
use App\Http\Requests\ShowEmailAvailability;
use App\Http\Requests\ShowHrepIdAvailability;
use App\Http\Requests\StoreUser;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    /**
     * Register a new user
     * @param StoreUser $request
     * @return Response
     */
    public function register(StoreUser $request)
    {
        $request['password'] = Hash::make($request['password']);
        $newUser = User::create($request->all());

        Auth::attempt(['hrep_id' => $request['hrep_id'], 'password' => $request['password']]);

        $token = $newUser->createToken('api_token')->plainTextToken;
        $type = 'Bearer';

        return response(['user' => $newUser, 'access_token' => $token, 'token_type' => $type], 201);
    }

    /**
     * Login to an existing user
     * @param LoginUser $request
     * @return Response
     */
    public function login(LoginUser $request)
    {
        if (!Auth::attempt($request->all())) {
            return throw ApiErrorResponse::createErrorResponse('Invalid hrep_id or password.', NULL, 401, ApiErrorResponse::$INVALID_CREDENTIALS_CODE);
        }

        $token = Auth::user()->createToken('api_token')->plainTextToken;
        $type = 'Bearer';
        return response(['user' => Auth::user(), 'access_token' => $token, 'token_type' => $type]);
    }

    /**
     * Revoke the current access token of the auth user
     *
     * @return Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response(204);
    }

    public function showHrepIdAvailability(ShowHrepIdAvailability $request)
    {
        $hrepIdExists = User::find($request['hrep_id']);

        if (!$hrepIdExists) return response(['hrep_id_available' => true], 200);
        else return response(['hrep_id_available' => false], 200);
    }

    public function showEmailAvailability(ShowEmailAvailability $request)
    {
        $emailExists = User::where('email', '=', $request['email'])->first();

        if (!$emailExists) return response(['email_available' => true], 200);
        else return response(['email_available' => false], 200);
    }

}
