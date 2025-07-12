<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use Illuminate\Http\Request;
use App\Models\User;
use G4T\Swagger\Attributes\SwaggerSection;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

#[SwaggerSection('APIs for Auth')]
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->where('status', 'active')->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $refreshToken = $this->createRefreshToken();        

        $user->refresh_token = $refreshToken;

        $user->save();
        
        return $this->respondWithToken($token, $refreshToken);
    }

    

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $exception) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $authHeader = request()->header('Authorization');

        $accessToken = Str::replaceFirst('Bearer ', '', $authHeader);
        
        $decodeAccessToken = JWTAuth::getJWTProvider()->decode($accessToken);
        
        $user = User::find($decodeAccessToken['user_id']);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $user->refresh_token = '';
        
        $user->save();

        auth()->logout();

        return response()->json(['status' => 200, 'message' => 'Success'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(RefreshTokenRequest $request)
    {
        $authHeader = request()->header('Authorization');

        $accessToken = Str::replaceFirst('Bearer ', '', $authHeader);

        try {
            $decodeRefreshToken = JWTAuth::getJWTProvider()->decode($request->refreshToken);
            
            $decodeAccessToken = JWTAuth::getJWTProvider()->decode($accessToken);
            if ($decodeAccessToken) {
                 auth('api')->invalidate();
            }
            
            // $user = User::find($decodeRefreshToken['user_id']);
            $user = User::where('id', $decodeRefreshToken['user_id'])->where('status', 'active')->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            if ($user->refresh_token !== $request->refreshToken) {
                return response()->json(['error' => 'Refresh Token Invalid'], 500);
            }
            
            $newAccessToken = auth()->login($user);

            $newRefreshToken = $this->createRefreshToken();

            $user->refresh_token = $newRefreshToken;
            $user->save();

            return $this->respondWithToken($newAccessToken, $newRefreshToken);
        } catch (JWTException $e) {
            Log::error($e);
            return response()->json(['error' => 'Refresh Token Invalid'], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken($token, $refreshToken)
    {
        $accessCookie = cookie(
            'access_token', $token, 60, null, null, true, true, false, 'Strict'
        );
        
        $refreshCookie = cookie(
            'refresh_token', $refreshToken, 60 * 24 * 7, null, null, true, true, false, 'Strict'
        );

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ], 200)->withCookie($accessCookie)->withCookie($refreshCookie);
    }

    private function createRefreshToken() {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl'),
        ];

        $refreshToken = JWTAuth::getJWTProvider()->encode($data);

        return $refreshToken;
    }
}
