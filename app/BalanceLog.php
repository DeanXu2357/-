<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    const TRANS_PAY = 'pay';
    const TRANS_RECEIVE = 'receive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'transaction_type', 'amount'];
}
