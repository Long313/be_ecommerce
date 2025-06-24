<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use G4T\Swagger\Attributes\SwaggerSection;

#[SwaggerSection('APIs for Products')]
class ProductController extends Controller
{
    public function getProducts()
    {
        try {
            $products = Product::all();

            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Data retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
            ], 500);
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product,
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
     * Store a newly created resource in storage.
     */
    public function createProduct(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteProduct(string $id)
    {
        //
    }
}
