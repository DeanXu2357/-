<?php

namespace App\Http\Controllers;

use App\Balance;
use App\BalanceLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController
{
    public function trans(Request $request)
    {
        $request->validate(
            [
                'amount' => 'required|integer|min:1',
                'payer_id' => 'required|integer',
                'receiver_id' => 'required|integer',
            ]
        );

        $amount = (int)($request->get('amount'));
        $payerId = $request->get('payer_id');
        $receiverId = $request->get('receiver_id');

        DB::beginTransaction();
        try {
            $payer = Balance::where('user_id', $payerId)->first();
            $receiver = Balance::where('user_id', $receiverId)->first();

            if ($payer === null || $receiver === null) {
                DB::rollBack();
                return new JsonResponse(['msg' => 'balance not exist'], Response::HTTP_BAD_REQUEST);
            }

            if ($payer->balance < $amount) {
                DB::rollBack();
                return new JsonResponse(['msg' => 'payer do not have enough money'], Response::HTTP_BAD_REQUEST);
            }

            $payer->update(['balance' => (int)bcsub($payer->balance, $amount)]);
            $receiver->update(['balance' => (int)bcadd($receiver->balance, $amount)]);

            BalanceLog::create(['user_id' => $payerId, 'amount' => $amount, 'transaction_type' => BalanceLog::TRANS_PAY]);
            BalanceLog::create(['user_id' => $receiverId, 'amount' => $amount, 'transaction_type' => BalanceLog::TRANS_RECEIVE]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            return new JsonResponse(['msg' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['msg' => 'ok']);
    }

//    public function transOneTime(Request $request)
//    {
//        $request->validate(
//            [
//                'amount' => 'required|integer|min:1',
//                'payer_id' => 'required|integer',
//                'receiver_id' => 'required|integer',
//            ]
//        );
//
//        $amount = (int)($request->get('amount'));
//        $payerId = $request->get('payer_id');
//        $receiverId = $request->get('receiver_id');
//
//        DB::transaction(function () use ($amount, $receiverId, $payerId) {
//            $affect = DB::affectingStatement('');
//
//            if ($affect === 0) {
//                throw new \Exception('transaction failed');
//            }
//
//            BalanceLog::create(['user_id' => $payerId, 'amount' => $amount, 'transaction_type' => BalanceLog::TRANS_PAY]);
//            BalanceLog::create(['user_id' => $receiverId, 'amount' => $amount, 'transaction_type' => BalanceLog::TRANS_RECEIVE]);
//        });
//
//        return new JsonResponse(['msg' => 'ok']);
//    }
}
