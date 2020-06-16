<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->getItems();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $group = Group::create([
            "name" => $request->name,
            "code" => strtoupper(substr(md5(time()), 0, 8)),
            "owner" => $user->id
        ]);
        $user->groups()->attach($group->id, ['active' => 1]);
        return $group->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Group::where('id', $id)->with(['users' => function ($query) {
            $query->select('name', 'email')->withSum(['orders:price as credit', 'payments:payment as debit']);
        }])->first();
        $data->users = $data->users->map(function ($item, $key) {
            if ($item['debit'] != null && $item['credit'] != null) {
                return $item['total'] = $item->debit - $item->credit;
            } else {
                return $item['total'] = 0;
            }
            return $item;
        });
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Group::where('id', $id)->update($request->only('name'));
        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::where('id', $id)->first();
        $group->goods()->delete();
        $group->delete();
        return $this->getItems();
    }

    public function getItems()
    {
        $items = Group::auth()->withCount('usersActive as count')->get();
        if ($items->count() == 0) {
            return [];
        }
        return [[
            'name' => 'Группы, в которых вы состоите',
            'items' => $items
        ]];
    }
}
