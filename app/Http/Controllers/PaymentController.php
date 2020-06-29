<?php

namespace App\Http\Controllers;

use App\Group;
use App\Payment;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->checkCan($request->group_id);
        $user = User::whereHas('groups', function (Builder $query) use ($request) {
            $query->where('id', $request->group_id);
        })->find($request->user_id);
        if ($user) {
            $payment = new Payment($request->all());
            return $user->payments()->save($payment);
        }
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
        $payment = Payment::find($id);
        if ($payment) {
            $this->checkCan($payment->group_id);
            return $payment->update(['payment' => $request->payment]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if ($payment) {
            $this->checkCan($payment->group_id);
            return $payment->delete();
        }
        return false;
    }

    private function checkCan($id)
    {
        $group = Group::auth()->find($id);
        if (!$group) {
            abort(422, "У вас нет доступа");
        }
    }
}
