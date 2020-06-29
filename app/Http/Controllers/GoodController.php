<?php

namespace App\Http\Controllers;

use App\Good;
use App\Group;
use App\Http\Requests\GoodRequest;
use Illuminate\Http\Request;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Group::auth()->whereHas('goods')->with('goods')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoodRequest $request)
    {
        $this->checkCan($request->group_id);
        Good::create($request->all());
        return ['success' => true];
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
        $this->checkCan($request->group_id);
        Good::where('id', $id)->update($request->all());
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
        $good = Good::find($id);
        if ($good) {
            $this->checkCan($good->group_id);
            $good->delete();
        }
        return $this->index();
    }

    private function checkCan($id)
    {
        $group = Group::auth()->find($id);
        if (!$group) {
            abort(422, "У вас нет доступа");
        }
    }
}
