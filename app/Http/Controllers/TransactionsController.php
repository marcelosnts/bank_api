<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Transaction;
use App\UserBalance;

class TransactionsController extends Controller
{
    public function history(Request $request)
    {
        $user_id = $request->user()->id;
        $transactions = Transaction::where(compact('user_id'))->get();
        $balance = UserBalance::where(compact('user_id'))->first();

        $deposits = array_reduce($transactions->toArray(), function($accumulator, $transaction){
            if ($transaction['type'] == Transaction::DEPOSIT) {
                var_dump($accumulator);
                return $accumulator += floatval($transaction['value']);
            }

            return $accumulator;
        }, 0);

        $withdraws = array_reduce($transactions->toArray(), function($accumulator, $transaction){
            if ($transaction['type'] == Transaction::WITHDRAW) {
                var_dump($accumulator);
                return $accumulator -= floatval($transaction['value']);
            }

            return $accumulator;
        }, 0);

        $status = 200;
        return response()->json([
            compact('transactions', 'balance', 'deposits', 'withdraws', 'status')
        ], 200);
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
            $user_balance = UserBalance::where(compact('user_id'))
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
            ], 200);
        }

        DB::rollBack();
        return response()->json([
            'message' => "Something went wrong. Please try again later!"
        ], 500);
    }
}
