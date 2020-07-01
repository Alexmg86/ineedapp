<?php

namespace App\Http\Controllers;

use App\Access;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group_id = $request->group_id;

        $can = \DB::table('group_user')->where([['group_id', $group_id], ['user_id', Auth::id()]])->count();

        if (!$can) {
            return new Response(["У вас нет доступа"], 422);
        }

        $data = $request->only(['group_id', 'role_id', 'user_id']);

        if ($request->switch === 'true') {
            Access::updateOrCreate($data);
        } else {
            Access::where($data)->delete();
        }

        // $userInfo = User::where('id', $request->user_id)->first();
        // if ($userInfo) {
        //     $is_owner = $userInfo->owner == $userInfo->id ? true : false;
        //     $userInfo["roles"] = Role::when(!$is_owner, function ($query) use ($group_id, $userInfo) {
        //         return $query->withCount(['acceses' => function ($query) use ($group_id, $userInfo) {
        //             $query->where([['group_id', $group_id], ['user_id', $userInfo->id]]);
        //         }]);
        //     })->get()->map(function ($item, $key) use ($is_owner) {
        //         $item->can = $is_owner || $item->acceses_count ? true : false;
        //         return $item;
        //     });
        //     $userInfo->owner = $is_owner;
        // }
        return true;
    }
}
