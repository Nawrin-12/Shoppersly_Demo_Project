<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): JsonResponse
    {
        try {
            $products = Product::with('images')->get();
            
            return response()->json([
                'success' => true,
                'products' => ProductResource::collection($products)
            ]);
        } catch (\Exception $exception) {
            Log::error('Failed to fetch products: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products'
            ], 500);
        }
    }

    /**
     * Store a newly created product with multiple images.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'url' => 'required|url',
                'status' => 'required|in:available,unavailable',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Get the authenticated user
            $user = Auth::user();

            // Check if user is vendor
            if ($user->role !== 'vendor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only vendors can create products'
                ], 403);
            }

            // Create product with user_id
            $productData = $request->only(['name', 'description', 'category', 'price', 'url', 'status']);
            $productData['user_id'] = $user->id;
            
            $product = Product::create($productData);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('product_images', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'product' => new ProductResource($product->load('images')),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $exception) {
            Log::error('Product creation failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Product creation failed. Please try again',
            ], 500);
        }
    }

    /**
     * Update an existing product.
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Get the authenticated user instead of finding by email
            $user = Auth::user();

            // Check if user is vendor
            if ($user->role !== 'vendor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only vendors can update products'
                ], 403);
            }

            // Find the product that belongs to this vendor
            $product = Product::where('id', $validated['product_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or you are not authorized to update this product'
                ], 404);
            }

            // Update the product
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'url' => $validated['url'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'product' => new ProductResource($product->load('images'))
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $exception) {
            Log::error('Product update failed: ' . $exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Product update failed. Please try again',
            ], 500);
        }
    }
}