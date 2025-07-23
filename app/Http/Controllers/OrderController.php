<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderImage;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderApprovedMail;

class OrderController extends Controller
{
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

    /**
     * Approve an order and send email to customer.
     */
    public function approve($id): JsonResponse
    {
        $order = Order::findOrFail($id);

        // Update status to 'approved'
        $order->update(['status' => 'approved']);

        // Send approval email to customer
        Mail::to($order->customer_email)->send(new OrderApprovedMail($order));

        return response()->json([
            'success' => true,
            'message' => 'Order approved and email sent successfully.',
            'order' => new OrderResource($order->load('images')),
        ]);
    }
}