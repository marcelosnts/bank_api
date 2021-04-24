<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type', 'value', 'user_id'
    ];

    public const DEPOSIT = 1;
    public const WITHDRAW = 2;

    public static function getTypeText($type)
    {
        $labels = [
            1 => 'Deposit',
            2 => 'Withdraw'
        ];
        return $labels[$type];
    }
}
