<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePassword;
use App\Http\Requests\UpdateProfile;
use App\Http\Requests\UploadProfilePicture;
use App\Traits\ApiResponder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponder;

    /**
     * Show current auth user profile
     *
     * @return Response
     */
    public function show()
    {
        $user = Auth::user();
        return $this->success(['user' => $user], 200);
    }

    /**
     * Update current auth user profile
     * @param UpdateProfile $request
     * @return Response
     */
    public function update(UpdateProfile $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        return $this->success(['user' => $user], 200);
    }

    /**
     * Update current user's password
     * @param UpdatePassword $request
     * @return Response
     */
    public function changePassword(UpdatePassword $request)
    {
        $user = Auth::user();
        $request['password'] = Hash::make($request['password']);
        $user->update(['password' => $request['password']]);

        return $this->success(NULL, 204);
    }

    /**
     * Upload current profile picture
     * @param UploadProfilePicture $request
     * @return Response
     */
    public function uploadProfilePicture(UploadProfilePicture $request)
    {
        $user = Auth::user();
        $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
        $filePath = $request->file('image')->storeAs('uploads/profile_pictures', $fileName, 'public');
        $user->profile_picture_url = '//storage//' . $filePath;
        $user->save();

        return $this->success(['user' => $user], 200);
    }

}
