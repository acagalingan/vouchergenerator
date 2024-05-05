<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    Voucher
};
use App\Policies\VoucherPolicy;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class VoucherController extends Controller
{
    protected $vouchersPolicy;

    public function __construct(VoucherPolicy $vouchersPolicy)
    {
        $this->vouchersPolicy = $vouchersPolicy;
    }

    public function list(Request $request)
    {
        try {
            $userId = Auth::user()->id;

            $list = User::with('vouchers')->where('id', $userId)->get();

            return response()->json([
                'data' => $list,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function generate(Request $request)
    {
        try {
            $userId = Auth::user()->id;

            if($this->vouchersPolicy->userCanStillGenerateVoucher($userId)){
                DB::beginTransaction();
                $code = Voucher::generateVoucherCode();
                $voucher = Voucher::create([
                    'user_id' => $userId,
                    'code' => $code
                ]);

                DB::commit();

                return response()->json([
                    'message' => "Voucher Generated Successfully",
                    'voucher' => $voucher
                ], 200);
            } else{
                throw new \Exception("Unable to generate any more voucher codes.");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            
            if ($validator->fails()) {
                throw new \Exception("Voucher Code is required");
            }

            $voucher = Voucher::where([
                'user_id' => $userId,
                'code' => $request->code
            ])->first();

            if(empty($voucher)) {
                throw new \Exception("Voucher Code does not exist");
            }

            DB::beginTransaction();
    
            $result = Voucher::where('id', $voucher->id)->delete();

            if(!$result) {
                throw new \Exception("Voucher Deletion has failed");
            }

            DB::commit();

            return response()->json([
                'message' => "Deleted Successfully",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
