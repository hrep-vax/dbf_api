<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use App\Http\Requests\LoginUser;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\ShowEmailAvailability;
use App\Http\Requests\StoreForgotPassword;
use App\Http\Requests\StoreUser;
use App\Mail\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        Auth::attempt(['email' => $request['email'], 'password' => $request['password']]);

        $token = $newUser->createToken('api_token')->plainTextToken;
        $type = 'Bearer';

        // TODO: Implement Spatie
        $roles = ['regular'];

        return response(['user' => $newUser, 'roles' => $roles, 'access_token' => $token, 'token_type' => $type], 201);
    }

    /**
     * Login to an existing user
     * @param LoginUser $request
     * @return Response
     */
    public function login(LoginUser $request)
    {
        if (!Auth::attempt($request->all())) {
            return throw ApiErrorResponse::createErrorResponse('Invalid email or password.', NULL, 401, ApiErrorResponse::$INVALID_CREDENTIALS_CODE);
        }

        $token = Auth::user()->createToken('api_token')->plainTextToken;
        $type = 'Bearer';

        // TODO: Implement Spatie
        $roles = ['regular'];

        return response(['user' => Auth::user(), 'roles' => $roles, 'access_token' => $token, 'token_type' => $type]);
    }

    /**
     * Revoke the current access token of the auth user
     *
     * @return Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    /**
     * Check if the email address is available for assignment
     * @param ShowEmailAvailability $request
     * @return Response
     */
    public function showEmailAvailability(ShowEmailAvailability $request)
    {
        $emailExists = User::where('email', '=', $request['email'])->first();

        if (!$emailExists) return response(['email_available' => true], 200);
        else return response(['email_available' => false], 200);
    }

    /**
     * Create a password reset request and send an email (SPA)
     * @param StoreForgotPassword $request
     * @return Response
     */
    public function spaForgotPassword(StoreForgotPassword $request)
    {
        $user = User::where('email', '=', $request['email'])->first();
        $token = $this->generateToken();

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $spa_ui_link = env('SPA_RESET_PASSWORD_URL');
        $reset_pass_link = $spa_ui_link . '?token=' . $token;

//        try {
//            Mail::to($user->email)->send(new PasswordReset($reset_pass_link));
//            return response(['message' => 'Password reset email sent to ' . $user['email']]);
//        } catch (\Exception $_e) {
//            return throw ApiErrorResponse::createErrorResponse('Failed to deliver email', null, 502, ApiErrorResponse::$SMTP_ERROR_CODE);
//        }

        Mail::to($user->email)->send(new PasswordReset($reset_pass_link));
        return response(['message' => 'Password reset email sent to ' . $user['email']]);

    }

    /**
     * Reset password via forgot password token
     * @param ResetPassword $request
     * @return Response
     */
    public function resetPassword(ResetPassword $request)
    {
        $resetRequest = DB::table('password_resets')
            ->where('email', $request['email'])
            ->where('token', $request['token'])
            ->first();

        if (!$resetRequest) {
            $errDescription = 'Incorrect email or reset password token. A new password reset request may have been issued or this request has already been used.';
            throw ApiErrorResponse::createErrorResponse($errDescription, NULL, 422, ApiErrorResponse::$VALIDATION_ERROR_CODE);
        }

        // Change current password
        $user = User::where('email', $request['email'])->first();
        $user->password = Hash::make($request['password']);
        $user->save();

        // Remove password reset request from the table
        DB::table('password_resets')->where('email', $request['email'])->delete();

        return response(['message' => 'Password reset successful.'], 200);
    }

    /**
     * Generate forgot password token
     * @return string
     */
    private function generateToken()
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return hash_hmac('sha256', Str::random(40), $key);
    }

}
