<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Transaction;

class UserBalance extends Model
{
    protected $table = 'user_balance';

    protected $fillable = [
        'balance', 'user_id',
    ];

    public static function calculateBalance($type, $value, $balance)
    {
        $balance = $type === Transaction::DEPOSIT ?
            $balance + $value :
            $balance - $value;

        return $balance;
    }
}
