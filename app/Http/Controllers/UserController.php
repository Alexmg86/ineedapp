<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser()
    {
    	return Auth::user();
    }

    public function update(Request $request)
    {
    	Auth::user()->update($request->all());
    	return Auth::user();
    }
}
