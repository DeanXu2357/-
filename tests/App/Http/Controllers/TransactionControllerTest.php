<?php

namespace App\Http\Controllers;

use App\Balance;
use App\BalanceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testTransSuccess()
    {
        $payer = factory(Balance::class)->create(['user_id' => 1, 'balance' => 50]);
        $receiver = factory(Balance::class)->create(['user_id' => 2, 'balance' => 100]);

        $r = $this->json('post', '/api/transaction', ['payer_id' => $payer->user_id, 'receiver_id' => $receiver->user_id, 'amount' => 25]);

        $r->assertStatus(200);
        $this->assertDatabaseHas('balance_logs', ['user_id' => $payer->user_id, 'amount' => 25, 'transaction_type' => BalanceLog::TRANS_PAY]);
        $this->assertDatabaseHas('balance_logs', ['user_id' => $receiver->user_id, 'amount' => 25, 'transaction_type' => BalanceLog::TRANS_RECEIVE]);
        $this->assertDatabaseHas('balances', ['user_id' => $payer->user_id, 'balance' => 25]);
        $this->assertDatabaseHas('balances', ['user_id' => $receiver->user_id, 'balance' => 125]);
    }

    public function testTransWithoutEnoughBalance()
    {
        $payer = factory(Balance::class)->create(['user_id' => 1, 'balance' => 50]);
        $receiver = factory(Balance::class)->create(['user_id' => 2, 'balance' => 100]);

        $r = $this->json('post', '/api/transaction', ['payer_id' => $payer->user_id, 'receiver_id' => $receiver->user_id, 'amount' => 75]);

        $r->assertStatus(400);
        $r->assertJsonFragment(['msg' => 'payer do not have enough money']);
    }
}
