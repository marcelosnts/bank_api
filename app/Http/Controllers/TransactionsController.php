<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;

class TransactionsController extends Controller
{
    private $type = [
        1 => 'Deposit',
        2 => 'Withdraw'
    ];

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

        $transaction = new Transaction([
            'type' => $request->type,
            'value' => $request->value,
            'user_id' => $request->user()->id
        ]);

        $transaction->save();

        return response()->json([
            'message' => "{$this->type[$request->type]} submited!"
        ], 201);
    }
}
