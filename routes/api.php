<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->summary('User login.');
    Route::get('profile', [AuthController::class, 'profile'])->summary("User's profile.");
    Route::post('logout', [AuthController::class, 'logout'])->summary('User logout.');
    Route::post('refresh', [AuthController::class, 'refresh'])->summary('User refresh token.');
});


#/ Define API routes for UserController
Route::get('user', [UserController::class, 'getUsers'])->summary('Get users');
Route::get('user/{id}', [UserController::class, 'getUserById'])->summary('Get user by ID');
Route::post('user/customer', [UserController::class, 'createCustomerUser'])->summary('Create a new user (customer)');
Route::post('user/verify-otp-register', [UserController::class, 'verifyOtpToRegister'])->summary('Verify OTP to register new account');
Route::post('user/resend-otp-register', [UserController::class, 'resendOtpToRegister'])->summary('Resend OTP to register new account');
Route::post('user/verify-otp-reset', [UserController::class, 'verifyOtpToResetPassword'])->summary('Verify OTP to reset password');
Route::put('user', [UserController::class, 'updateUser'])->summary('Update an user');
Route::delete('user', [UserController::class, 'deleteUser'])->summary('Delete an user');


#/ Define API routes for ProductController
Route::get('product', [ProductController::class, 'getProducts'])->summary('Get products');
Route::get('product/{id}', [ProductController::class, 'getProductById'])->summary('Get product by ID');
Route::post('product', [ProductController::class, 'createProduct'])->summary('Create a new product');
Route::put('product', [ProductController::class, 'updateProduct'])->summary('Update a product');
Route::delete('product', [ProductController::class, 'deleteProduct'])->summary('Delete a product');
