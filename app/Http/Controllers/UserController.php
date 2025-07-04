<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpEmailToRegister;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\ResendOtpRequest;
use G4T\Swagger\Attributes\SwaggerSection;

#[SwaggerSection('APIs for Users')]
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getUsers()
    {
        try {
            $user = User::all();

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Data retrieved successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Data retrieved successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createCustomerUser(CreateUserRequest $request)
    {
        try {
            $user = new User;
            $user->fill($request->all());
            $user->id = Str::uuid()->toString();
            $user->fullname = 'default full name';
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->status = 'pending';
            $user->refresh_token = '';
            $user->save();

            $otpData = $this->generateOtp();
            
            $this->saveEmailOtp($request->email, $otpData['hash'], 'register');

            $subject = '[Amax] - Your OTP Code to Complete Registration';
            $this->sendOtpEmail($request->email, $subject, $otpData['otp']);        

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteUser(string $id)
    {
        //
    }

    public function verifyOtpToRegister(VerifyOtpRequest $request) {
        try {
            $otpRecord = DB::table('email_otps')
            ->where('email', $request->email)
            ->where('purpose', 'register')
            ->where('expired_at', '>', Carbon::now())
            ->first();

            if(! $otpRecord || ! Hash::check($request->otp, $otpRecord->otp)) {
                return response()->json(['status' => 400, 'message' => 'OTP invalid or expired',], 400); 
            }

            DB::table('email_otps')->where('id', $otpRecord->id)->delete();

            User::where('email', $request->email)->update(['status' => 'active']);

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
         
    }

    public function resendOtpToRegister(ResendOtpRequest $request) {
        try {
            $otpData = $this->generateOtp();

            DB::table('email_otps')->where('email', $request->email)->where('purpose', 'register')->update([
                'otp' => $otpData['hash'],
                'expired_at' => Carbon::now()->addMinutes(5),
            ]);

            $subject = '[Amax] - Your OTP Code to Complete Registration';
            $this->sendOtpEmail($request->email, $subject, $otpData['otp']);

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch(\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
        
    }

    public function verifyOtpToResetPassword(VerifyOtpRequest $request) {
        //TODO: implement OTP verification to reset password
    }

    private function generateOtp() {
        $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $otpHash = Hash::make($otp);

        return ['otp' => $otp, 'hash' => $otpHash];
    }

    private function saveEmailOtp($email, $otpHash, $purpose) {
        try {
            DB::table('email_otps')->insert(
                ['id' => Str::uuid()->toString(),
                  'email' => $email, 
                  'purpose' => $purpose,
                  'otp' => $otpHash,
                  'expired_at' => Carbon::now()->addMinutes(5),  
                ],
            );
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function sendOtpEmail($toEmail, $subject, $otp) {
        try {
            Mail::to($toEmail)->send(new OtpEmailToRegister($subject, $otp));
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
