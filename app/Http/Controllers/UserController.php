<?php

namespace App\Http\Controllers;

use App\Order;
use App\Role;
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

        $userInfo = User::where('hash', $request->hash)
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
        }])
        ->withMax(['groups:owner as owner' => function ($query) use ($group_id) {
            $query->where('group_id', $group_id);
        }])->first();

        $userInfo->total = (int)$userInfo->debit - (int)$userInfo->credit;

        if ($userInfo) {
            $is_owner = $userInfo->owner == $userInfo->id ? true : false;
            $userInfo["roles"] = Role::when(!$is_owner, function ($query) use ($group_id, $userInfo) {
                return $query->withCount(['acceses' => function ($query) use ($group_id, $userInfo) {
                    $query->where([['group_id', $group_id], ['user_id', $userInfo->id]]);
                }]);
            })->get()->map(function ($item, $key) use ($is_owner) {
                $item->can = $is_owner || $item->acceses_count ? true : false;
                return $item;
            });
        }

        return $userInfo;
    }
}
