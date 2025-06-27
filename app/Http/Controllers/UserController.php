<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
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
            $user->password = bcrypt($request->password);
            $user->role = 'customer';
            $user->refresh_token = '';
            $user->save();
            return response()->json([
                'success' => true,
                // 'data' => $user,
                'message' => 'User created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User created unsuccessfully',
            ], 500);
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
}
