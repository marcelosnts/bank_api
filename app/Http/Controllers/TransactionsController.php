<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Transaction;
use App\UserBalance;

class TransactionsController extends Controller
{
    public function history()
    {
        return;
    }

    public function new(Request $request)
    {
        $rules = [
            'type' => 'required|min:1|max:2|integer',
            'value' => 'required|min:0|numeric'
        ];

        $request->validate($rules);

        $user_id = $request->user()->id;

        $transaction = new Transaction([
            'type' => $request->type,
            'value' => $request->value,
            'user_id' => $user_id
        ]);

        DB::beginTransaction();

        if ($transaction->save()) {
            $user_balance = UserBalance::where('user_id', $user_id)
                ->first();

            if (!$user_balance) {
                $user_balance = new UserBalance([
                    'balance' => 0,
                    'user_id' => $user_id
                ]);

                if (!$user_balance->save()) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "It was not possible to calculate your balance. Please try again later!"
                    ], 500);
                }
            }

            $user_balance->balance = UserBalance::calculateBalance(
                $request->type,
                $request->value,
                $user_balance->balance
            );

            if (!$user_balance->save()) {
                DB:rollBack();
                return response()->json([
                    'message' => "It was not possible to calculate your balance. Please try again later!"
                ], 500);
            }

            DB::commit();
            return response()->json([
                'message' =>  Transaction::getTypeText($request->type) . " submited!"
            ], 201);
        }

        DB::rollBack();
        return response()->json([
            'message' => "Something went wrong. Please try again later!"
        ], 500);
    }
}
