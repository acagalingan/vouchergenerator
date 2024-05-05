<?php

namespace App\Policies;

use App\Models\{
    Voucher
};

class VoucherPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function userCanStillGenerateVoucher($userId){
        $vouchers = Voucher::where('user_id', $userId)->get();
        $count = $vouchers->count();

        if($count < 10){
            return true;
        }

        return false;
    }
}
