<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        return response()->json(Order::where('vendor_id', $vendorId)->get());
    }
    public function userOrders(Request $request) {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json($orders);
    }
}
