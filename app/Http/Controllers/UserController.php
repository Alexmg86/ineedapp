<?php

namespace App\Http\Controllers;

use App\Order;
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

    public function getstat(Request $request)
    {
        $group_id = $request->group_id;
        return User::where('hash', $request->hash)
        ->withSum(['orders:price as credit' => function ($query) use ($group_id) {
            $query->where('group_id', $group_id);
        }])
        ->withSum(['payments:payment as debit' => function ($query) use ($group_id) {
            $query->where('group_id', $group_id);
        }])
        ->with(['orders' => function ($query) use ($group_id) {
            $query->where('group_id', $group_id)->limit(10);
        }])
        ->with(['payments' => function ($query) use ($group_id) {
            $query->where('group_id', $group_id)->limit(10);
        }])->first();

        return $data;
        return $request->all();
        // return User::where('hash', $id)->first();
    }
}
