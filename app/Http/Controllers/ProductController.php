<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use G4T\Swagger\Attributes\SwaggerSection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;


#[SwaggerSection('APIs for Products')]
class ProductController extends Controller
{   
    public function getProducts(Request $request) {
        try {
            $search = $request->query('search', '');
            $genders = $request->query('gender', []);
            $categories = $request->query('category', []);
            $type = $request->query('type', '');
            $startPrice = $request->query('startPrice', '');
            $endPrice = $request->query('endPrice', '');
            $pageSize = $request->query('pageSize', 5);
            $pageIndex = $request->query('pageIndex', 1);
            $sortOrder = $request->query('sort', 'desc');

            $query = Product::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
                });
            }

            if (!empty($genders)) {
                $query->whereIn('gender', (array) $genders);
            }

            if (!empty($categories)) {
                $query->whereIn('category', (array) $categories);
            }

            if ($type) {
                $query->where('type', $type);
            }

            if (is_numeric($startPrice)) {
                $query->where('price', '>=', $startPrice);
            }

            if (is_numeric($endPrice)) {
                $query->where('price', '<=', $endPrice);
            }
            
            $query->orderBy('created_at', $sortOrder);

            $products = $query->paginate($pageSize, ['*'], 'page', $pageIndex);

            return response()->json([
                'status' => 200,
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
                'message' => 'Success',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProductById($id) {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'status' => 200,
                'data' => $product,
                'message' => 'Success',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createProduct(CreateProductRequest $request) {
        $loginInfo = $this->checkAdminLogin();

        if (!$loginInfo['isAdmin']) {
            return response()->json(['status' => 403, 'message' => 'Unauthorized',], 403);
        }

        $imageUrl = $this->uploadImage($request);

        try {
            $product = new Product;
            $product->id = Str::uuid()->toString();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category = $request->category;
            $product->gender = $request->gender;
            $product->discount_rate = $request->discountRate;
            $product->tax_rate = $request->taxRate;
            $product->inventory_count = $request->inventoryCount;
            $product->image_url = $imageUrl;
            $product->star = $request->star;
            $product->type = $request->type;
            $product->is_active = true;
            $product->created_by = $loginInfo['userId'];
            $product->save();

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function updateProduct(UpdateProductRequest $request) {
        $loginInfo = $this->checkAdminLogin();

        if (!$loginInfo['isAdmin']) {
            return response()->json(['status' => 403, 'message' => 'Unauthorized',], 403);
        }

        $product = Product::where('id', $request->id)->first();

        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found',], 404);
        }

        $imageUrl = $this->uploadImage($request);

        try {
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category = $request->category;
            $product->gender = $request->gender;
            $product->discount_rate = $request->discountRate;
            $product->tax_rate = $request->taxRate;
            $product->inventory_count = $request->inventoryCount;
            $product->image_url = $imageUrl;
            $product->star = $request->star;
            $product->type = $request->type;
            $product->is_active = $request->isActive;
            $product->updated_by = $loginInfo['userId'];
            $product->save();

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteProduct($id) {
        $loginInfo = $this->checkAdminLogin();

        if (!$loginInfo['isAdmin']) {
            return response()->json(['status' => 403, 'message' => 'Unauthorized',], 403);
        }

        $product = Product::findOrFail($id);

        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found',], 404);
        }

        $orderItem = OrderItem::where('product_id', $id)->first();

        if ($orderItem) {
            return response()->json(['status' => 400, 'message' => 'Product is used. Can not delete',], 400);
        }

        try {
            $product->deleted_by = $loginInfo['userId'];
            $product->save();
            $product->delete();

            return response()->json(['status' => 200, 'message' => 'Success',], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function checkAdminLogin() {
        try {
            $payload = JWTAuth::parseToken()->getPayload();

            if (!$payload) {
                return response()->json(['status' => 500, 'message' => 'Invalid token'], 500);
            }

            return [
                'isAdmin' => $payload->get('role') === 'admin',
                'userId' => $payload->get('user_id'),
            ];
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'Fail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function uploadImage($request) {
        try {
            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
     
            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            $url = asset('storage/' . $imageName);

            return $url;
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => "Upload image error"
            ],500);
        }
    }
}
