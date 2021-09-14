<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use App\Http\Requests\LoginUser;
use App\Http\Requests\ResetPassword;
use App\Http\Requests\ShowEmailAvailability;
use App\Http\Requests\StoreForgotPassword;
use App\Http\Requests\StoreUser;
use App\Mail\PasswordReset;
use App\Mail\PasswordResetOtp;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponder;

    /**
     * Register a new user
     * @param StoreUser $request
     * @return Response
     */
    public function register(StoreUser $request)
    {
        $request['password'] = Hash::make($request['password']);
        $newUser = User::create($request->only('email', 'password'));
        $newUser->userInfo()->create($request->except('email', 'password', 'password_confirmation'));

        // Re-assignment to run eager loading of user_info
        $newUser = User::find($newUser->id);

        Auth::attempt(['email' => $request['email'], 'password' => $request['password']]);

        $token = $newUser->createToken('api_token')->plainTextToken;
        $type = 'Bearer';

        // Assign regular user permissions
        $newUser->assignRole('regular');
        $rolesNames = $newUser->getRoleNames();

        return $this->success(['user' => $newUser->flattenUserInfo(), 'roles' => $rolesNames, 'access_token' => $token, 'token_type' => $type], 201);
    }

    /**
     * Login to an existing user
     * @param LoginUser $request
     * @return Response
     */
    public function login(LoginUser $request)
    {
        if (!Auth::attempt($request->all())) {
            $this->throwError('Invalid email or password.', NULL, 401, ApiErrorResponse::INVALID_CREDENTIALS_CODE);
        }

        $token = Auth::user()->createToken('api_token')->plainTextToken;
        $type = 'Bearer';

        // Get roles
        $roles = Auth::user()->getRoleNames();

        return $this->success(['user' => Auth::user()->flattenUserInfo(), 'roles' => $roles, 'access_token' => $token, 'token_type' => $type], 200);
    }

    /**
     * Revoke the current access token of the auth user
     *
     * @return Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success(NULL, 204);
    }

    /**
     * Check if the email address is available for assignment
     * @param ShowEmailAvailability $request
     * @return Response
     */
    public function showEmailAvailability(ShowEmailAvailability $request)
    {
        $emailExists = User::where('email', '=', $request['email'])->first();

        if (!$emailExists) return $this->success(['email_available' => true], 200);
        else return $this->success(['email_available' => false], 200);
    }

    /**
     * Create a password reset request and send an email (SPA)
     * @param StoreForgotPassword $request
     * @return Response
     */
    public function forgotPassword(StoreForgotPassword $request)
    {
        $user = User::where('email', '=', $request['email'])->first();
        $type = $request['type'];

        if ($type === 'spa' || !$type) $token = $this->generateToken();
        else $token = $this->generateToken('mobile');

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $spa_ui_link = env('SPA_RESET_PASSWORD_URL');
        $reset_pass_link = $spa_ui_link . '?token=' . $token;

        try {
            if ($type === 'spa' || !$type) Mail::to($user->email)->send(new PasswordReset($reset_pass_link));
            else Mail::to($user->email)->send(new PasswordResetOtp($token));
        } catch (\Exception $_e) {
            $this->throwError('Failed to deliver email', null, 502, ApiErrorResponse::SMTP_ERROR_CODE);
        }

        return $this->success(['message' => 'Password reset email sent to ' . $user['email']], 200);
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
            $this->throwError($errDescription, NULL, 422, ApiErrorResponse::VALIDATION_ERROR_CODE);
        }

        // Change current password
        $user = User::where('email', $request['email'])->first();
        $user->password = Hash::make($request['password']);
        $user->save();

        // Remove password reset request from the table
        DB::table('password_resets')->where('email', $request['email'])->delete();

        return $this->success(['message' => 'Password reset successful.'], 200);
    }

    /**
     * Generate forgot password token
     * @return string
     */
    private function generateToken(string $type = NULL)
    {
        if (!$type || $type === 'spa') {
            $key = config('app.key');

            if (Str::startsWith($key, 'base64:')) {
                $key = base64_decode(substr($key, 7));
            }
            return hash_hmac('sha256', Str::random(40), $key);

        } else {
            return Str::upper(Str::random(6));
        }
    }
}
