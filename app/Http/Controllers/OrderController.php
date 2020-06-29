<?php

namespace App\Http\Controllers;

use App\Group;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
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
        $this->checkCan($request->group);
        Order::create([
            'user_id' => Auth::id(),
            'group_id' => $request->group,
            'good_id' => $request->good,
            'price' => $request->price
        ]);
        return $this->getItems();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $this->checkCan($order->group_id);
            $order->delete();
        }
        return $this->getItems();
    }

    private function getItems()
    {
        $items = Order::where('user_id', Auth::id())->orderByDesc('id')->limit(10)->get();
        if ($items->count() == 0) {
            return [];
        }
        return [[
            'name' => 'Последние покупки',
            'items' => $items
        ]];
    }

    private function checkCan($id)
    {
        $group = Group::auth()->find($id);
        if (!$group) {
            abort(422, "У вас нет доступа");
        } else {
            return $group;
        }
    }
}
