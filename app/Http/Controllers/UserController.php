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
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\OtpEmailToResetPassword;
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
    public function createCustomerUser(CreateUserRequest $request) {
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
            $this->sendOtpEmail($request->email, $subject, $otpData['otp'], 'register');        

            return response()->json(['status' => 200, 'message' => 'OTP sent to email',], 200);
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
            $user = User::where('email', $request->email)->where('status', 'pending')->first();

            if (! $user) {
                return response()->json(['status' => 404, 'message' => 'User not found',], 404);
            }

            $otpRecord = DB::table('email_otps')
            ->where('email', $request->email)
            ->where('purpose', 'register')
            ->where('expired_at', '>', Carbon::now())
            ->first();

            if(!$otpRecord || !Hash::check($request->otp, $otpRecord->otp)) {
                return response()->json(['status' => 400, 'message' => 'OTP invalid or expired',], 400); 
            }       
            
            DB::table('email_otps')->where('email', $request->email)->where('purpose', 'register')->delete();

            User::where('email', $request->email)->update(['status' => 'active']);

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
         
    }

    public function forgotPassword(ForgotPasswordRequest $request) {
        try {
            $user = User::where('email', $request->email)->where('status', 'active')->first();

            if (! $user) {
                return response()->json(['status' => 404, 'message' => 'User not found',], 404);
            }

            $otpData = $this->generateOtp();
            
            $this->saveEmailOtp($request->email, $otpData['hash'], 'reset');

            $subject = '[Amax] - Your OTP Code to Reset Password';
            $this->sendOtpEmail($request->email, $subject, $otpData['otp'], 'reset');

            return response()->json(['status' => 200, 'message' => 'OTP sent to email',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
          
    }

    public function resendOtp(ResendOtpRequest $request) {
        try {
            $user = null;

            if ($request->purpose === 'reset') {
                $user = User::where('email', $request->email)->where('status', 'active')->first();
            } else if ($request->purpose === 'register') {
                $user = User::where('email', $request->email)->where('status', 'pending')->first();
            }

            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'User not found',], 404);
            }            

            $otpData = $this->generateOtp();

            $this->saveEmailOtp($request->email, $otpData['hash'], $request->purpose);

            $subject = '[Amax] - Your OTP Code';

            if ($request->purpose == 'register') {
                $subject = '[Amax] - Your OTP Code to Complete Registration';
            } else if ($request->purpose == 'reset') {
                $subject = '[Amax] - Your OTP Code to Reset Password';
            }
                
            $this->sendOtpEmail($request->email, $subject, $otpData['otp'], $request->purpose);

            return response()->json(['status' => 200, 'message' => 'OTP sent to email',], 200);
        } catch(\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
    }

    public function verifyOtpToResetPassword(VerifyOtpRequest $request) {
        try {
            $user = User::where('email', $request->email)->where('status', 'active')->first();

            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'User not found',], 404);
            }

            $otpRecord = DB::table('email_otps')
            ->where('email', $request->email)
            ->where('purpose', 'reset')
            ->where('expired_at', '>', Carbon::now())
            ->first();

            if(!$otpRecord || !Hash::check($request->otp, $otpRecord->otp)) {
                return response()->json(['status' => 400, 'message' => 'OTP invalid or expired',], 400); 
            } 

            $payload = [
                'email' => $request->email,
                'purpose' => 'reset',
                'exp' => now()->addMinutes(10)->timestamp,
            ];

            $data = json_encode($payload);

            $signature = hash_hmac('sha256', $data, config('app.key'));

            $token = base64_encode($data . '|' . $signature);

            DB::table('email_otps')->where('id', $otpRecord->id)->update(['otp' => '', 'reset_token' => $token]);

            $resetCookie = cookie(
                'reset_token', $token, 60, null, null, true, true, false, 'Strict'
            );

            return response()->json(['status' => 200, 'message' => 'Success'], 200)->withCookie($resetCookie);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail',], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request) {
        try {
            $user = User::where('email', $request->email)->where('status', 'active')->first();

            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'User not found',], 404);
            }

            $resetToken = urldecode($request->resetToken);

            $decoded = base64_decode($resetToken);

            [$data, $signature] = explode('|', $decoded, 2);

            $expectedSignature = hash_hmac('sha256', $data, config('app.key'));

            if (!hash_equals($expectedSignature, $signature)) {
                return response()->json(['status' => 401, 'message' => 'Invalid token'], 401);
            }

            $payload = json_decode($data, true);

            if (now()->timestamp > $payload['exp']) {
                return response()->json(['status' => 401, 'message' => 'Token expired'], 401);
            }

            $otpRecord = DB::table('email_otps')
            ->where('email', $request->email)
            ->where('purpose', 'reset')
            ->where('reset_token', $resetToken)
            ->first();

            if(!$otpRecord) {
                return response()->json(['status' => 400, 'message' => 'Bad request'], 400);
            }

            DB::table('email_otps')->where('id', $otpRecord->id)->delete();

            User::where('id', $user->id)->update(['password' => Hash::make($request->newPassword)]);

            return response()->json(['status' => 200, 'message' => 'Success'], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, 'message' => 'Fail'], 500);
        }
    }

    private function generateOtp() {
        try {
            $otp = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $otpHash = Hash::make($otp);

            return ['otp' => $otp, 'hash' => $otpHash];
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function saveEmailOtp($email, $otpHash, $purpose) {
        try {
            DB::table('email_otps')->updateOrInsert(
                ['email' => $email, 'purpose' => $purpose,],
                [
                  'id' => Str::uuid()->toString(),
                  'otp' => $otpHash,
                  'reset_token' => '',
                  'expired_at' => Carbon::now()->addMinutes(5),
                  'updated_at' => now()  
                ]
            );
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function sendOtpEmail($toEmail, $subject, $otp, $purpose) {
        try {
            if ($purpose == 'register') {
                Mail::to($toEmail)->send(new OtpEmailToRegister($subject, $otp));
            } else if ($purpose == 'reset') {
                Mail::to($toEmail)->send(new OtpEmailToResetPassword($subject, $otp));
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
