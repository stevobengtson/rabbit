<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VirtualCurrency extends Model
{
    public static function transferMoney($sourceUserId = null, $destinationUserId, $amount)
    {
        VirtualCurrency::creditAmount($sourceUserId, $destinationUserId, $amount);

        if ($sourceUserId !== null) {
            VirtualCurrency::debitAmount(null, $sourceUserId, $amount);
        }
    }

    public static function creditAmount($sourceUserId = null, $destinationUserId, $amount)
    {
        VirtualCurrency::ledger($sourceUserId, $destinationUserId, $amount, null);
    }

    public static function debitAmount($sourceUserId = null, $destinationUserId, $amount)
    {
        VirtualCurrency::ledger($sourceUserId, $destinationUserId, null, $amount);
    }

    public static function ledger($sourceUserId = null, $destinationUserId, $credit = null, $debit = null)
    {
        $sourceVirtualCurrency = new VirtualCurrency();
        $sourceVirtualCurrency->source_user_id = $sourceUserId;
        $sourceVirtualCurrency->destination_user_id = $destinationUserId;
        $sourceVirtualCurrency->credit = $credit;
        $sourceVirtualCurrency->debit = $debit;
        $sourceVirtualCurrency->save();
    }

    public static function userBalance($userId)
    {
        $totals = VirtualCurrency::where('destination_user_id', $userId)
                    ->select(DB::raw("SUM(credit) as credit_sum, SUM(debit) as debit_sum"))
                    ->first();

        return ($totals->credit_sum - $totals->debit_sum);
    }
}
