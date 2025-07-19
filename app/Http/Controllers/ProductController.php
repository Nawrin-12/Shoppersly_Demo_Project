<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Displays a listing of products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'products' => ProductResource::collection(Product::all())
        ]);
    }

    /**
     * Stores a newly created product with multiple images.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'url' => 'required|url',
            'status' => 'required|in:available,unavailable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create($request->only(['name', 'description', 'category', 'price', 'url', 'status']));

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
