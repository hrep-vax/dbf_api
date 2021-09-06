<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show current auth user profile
     *
     * @return Response
     */
    public function show()
    {
        $user = Auth::user();
        return response(['user' => $user], 200);
    }

    /**
     * Update current auth user profile
     *
     * @return Response
     */
    public function update(UpdateProfile $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        return response(['user' => $user], 200);
    }


}
