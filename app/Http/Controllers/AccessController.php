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

        return true;
    }
}
