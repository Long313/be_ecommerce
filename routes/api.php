<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::apiResource('user', UserController::class);

#/ Define API routes for UserController
Route::get('user', [UserController::class, 'getUsers'])->summary('Get users');
Route::get('user/{id}', [UserController::class, 'getUserById'])->summary('Get user by ID');
Route::post('user', [UserController::class, 'createUser'])->summary('Create a new user');
Route::put('user', [UserController::class, 'updateUser'])->summary('Update an user');
Route::delete('user', [UserController::class, 'deleteUser'])->summary('Delete an user');


#/ Define API routes for ProductController
Route::get('product', [ProductController::class, 'getProducts'])->summary('Get products');
Route::get('product/{id}', [ProductController::class, 'getProductById'])->summary('Get product by ID');
Route::post('product', [ProductController::class, 'createProduct'])->summary('Create a new product');
Route::put('product', [ProductController::class, 'updateProduct'])->summary('Update a product');
Route::delete('product', [ProductController::class, 'deleteProduct'])->summary('Delete a product');
