<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return Auth::user();
    }

    public function update(Request $request, $id)
    {
        User::where('hash', $id)->update($request->all());
        return User::where('hash', $id)->first();
    }

    public function loginhash(Request $request)
    {
        return Auth::user();
    }
}
