<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
//use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function update(UpdateRequest $request):JsonResponse
    {
        $validated = $request->validated();
        try{
            $user= User::query()->where('email', $validated['email'])->first();
            if(!$user){
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
            if($user->role=='vendor'){
                $product= Product::query()->where('id', $validated['product_id'])
                    ->where('user_id',$user->id)
                    ->first();
                if(!$product){
                    return response()->json([
                        'message' => 'Product not found'
                    ]);
                }
                $product->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'category' => $validated['category'],
                    'price' => $validated['price'],
                    'url' => $validated['url'],
                    'status' => $validated['status'],
                ]);
                return response()->json([
                    'message' => 'Product updated successfully'
                ]);

            }
            else{
                return response()->json([
                    'message' => 'You are not authorized to update this product'
                ]);
            }

        }catch(\Exception $exception){
            Log::error('FULL Error:' .$exception->getMessage());
            return response()->json([
                'message' =>'Something went wrong. Please try again',
                'error' => $exception->getMessage()
            ]);
        }

// use App\Models\Product;
// use Illuminate\Http\JsonResponse;
// use App\Http\Resources\ProductResource;

// class ProductController extends Controller
// {
//     /**
//      * Display a listing of products.
//      *
//      * @return JsonResponse
//      */
//     public function index(): JsonResponse
//     {
//         return response()->json([
//             'success' => true,
//             'products' => ProductResource::collection(Product::all())
//         ]);

//     }

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

    public function  delete(DeleteRequest $request):jsonResponse
    {
        $validated = $request->validated();
        try{
            $user= User::query()->where('email', $validated['email'])->first();
            if(!$user){
                return response()->json([
                    'message' => 'User not found'
                ]);
            }
            if(!$user->role=='vendor'||!$user->role=='admin'){
                return response()->json([
                    'message' => 'You are not authorized to delete this product'
                ]);
            }
            $product= Product::query()->where('id', $validated['product_id'])
                ->where('user_id',$user->id)
                ->first();
            if(!$product){
                return response()->json([
                    'message' => 'Product not found'
                ]);
            }
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully'
            ]);

        }catch(\Exception $exception){
            Log::error('FULL Error:' .$exception->getMessage());
            return response()->json([
                'message' =>'Something went wrong. Please try again',
                'error' => $exception->getMessage()
            ]);
        }
    }

        public function SoftDeletedData(): JsonResponse{
            $softDeletedProd= Product::withTrashed()->where("deleted_at", '>', Carbon::now()->subWeek())->get();
            return response()->json([
                'softDeletedProd' => $softDeletedProd,
                'message' => 'Soft Deleted Data'
            ]);

    }
}
