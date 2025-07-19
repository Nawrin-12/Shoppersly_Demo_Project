<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        
        $products = Product::with('images')->get();

        return response()->json([
            'success' => true,
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * Store a newly created product with multiple images.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validation
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'url' => 'required|url',
            'status' => 'required|in:available,unavailable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Product create
        $product = Product::create($request->only(['name', 'description', 'category', 'price', 'url', 'status']));

        // Multiple images upload and save
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return response()->json([
            'success' => true,
            'product' => new ProductResource($product->load('images')),
        ]);
    }
}
