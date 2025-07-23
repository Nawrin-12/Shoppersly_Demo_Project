<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\OrderImage;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller{
    public function store(Request $request): JsonResponse
{
    $request->validate([
        'customer_name' => 'required|string',
        'customer_email' => 'required|email',
        'product_details' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $order = Order::create($request->only('customer_name', 'customer_email', 'product_details'));

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('order_images', 'public');
            $order->images()->create(['image_path' => $path]);
        }
    }

    return response()->json([
        'success' => true,
        'order' => new OrderResource($order->load('images'))
    ]);
}

    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($user->role === 'admin') {
            $orders = Order::with('product', 'user')->get();
        } elseif ($user->role === 'vendor') {
            $productIds = $user->products()->pluck('id');
            $orders = Order::with('product', 'user')->whereIn('product_id', $productIds)->get();
        } else {
//            $orders = $user->orders()->with('product')->get();
            $orders = Order::with('product')->where('customer_email',$user->email)->get();

        }

        return response()->json([
            'message' => 'Orders retrieved successfully',
            'orders' => $orders,
        ]);
    }

}
}
 

