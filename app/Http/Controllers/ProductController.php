<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        return response()->json([
            'success' => true,
            'products' => ProductResource::collection(Product::all())
        ]);
    }
}
