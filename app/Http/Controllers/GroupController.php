<?php

namespace App\Http\Controllers;

use App\Group;
use App\Order;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $stat = [];
        $data = Group::find($id)->auth()
        ->with(['users' => function ($query) use ($id) {
            $query->select('name', 'email', 'id')
            ->withSum(['orders:price as credit' => function (Builder $query) use ($id) {
                $query->where('group_id', $id);
            }])
            ->withSum(['payments:payment as debit' => function (Builder $query) use ($id) {
                $query->where('group_id', $id);
            }]);
        }])->first();

        $stat['user'] = null;
        $maxCredit = $data->users->sortByDesc('credit')->first();
        $stat['total'] = $data->users->sum('credit');
        if ($maxCredit->credit != null) {
            $stat['user'] = $maxCredit;
        }

        $data->users = $data->users->map(function ($item, $key) {
            return $item['total'] = (int)$item->debit - (int)$item->credit;
            return $item;
        });

        $stat['good']  = DB::table('orders')->groupBy('good_id')
        ->addSelect('goods.icon_id', 'goods.name', DB::raw('count(*) as total'))
        ->leftJoin('goods', 'goods.id', '=', 'orders.good_id')
        ->orderByDesc('total')->limit(1)->get();

        return ["users" => [$data], "stats" => [$stat]];
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
