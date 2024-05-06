<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    User,
    Voucher
};
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users',
                'first_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);
            
            if ($validator->fails()) {
                throw new \Exception("Registration failed due to invalid credentials.");
            }

            DB::beginTransaction();
    
            $user = User::create([
                'username' => $request->username,
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $code = Voucher::generateVoucherCode();
            $voucher = Voucher::create([
                'user_id' => $user->id,
                'code' => $code
            ]);

            DB::commit();

            $details = [
                'first_name' => $request->first_name,
                'email' => $request->email,
            ];

            SendEmail::dispatch($details);

            return response()->json([
                'message' => "Registered Successfully",
                'user' => $user,
                'voucher' => $voucher
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
