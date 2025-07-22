<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(){
        $user = Auth::user();
        if($user->role == 'admin'){
            $orders = Order::all();
        }
        elseif ($user->role == 'vendor') {
            $productIds = $user->products()->pluck('product_id');
        }
    }
}
