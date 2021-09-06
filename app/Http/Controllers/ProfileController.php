<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
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
    public function index()
    {
        $user = Auth::user();
        return response(['user' => $user], 200);
    }
}
