<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User; // ✅ Add this to help PHPStan/Intelephense understand the user type
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Vendor’s own orders
    public function index()
    {
        /** @var User $user */
        $user = Auth::user(); // ✅ Hint to the IDE that this is your custom User

        if (! $user->hasAbility('view_vendor_orders')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(
            Order::where('vendor_id', $user->id)->get()
        );
    }

    // Authenticated user’s order history
    public function userOrders(Request $request)
    {
        /** @var User $user */
        $user = $request->user(); // ✅ Hint to the IDE that this is your custom User

        if (! $user->hasAbility('view_own_orders')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $orders = Order::where('user_id', $user->id)->get();
        return response()->json($orders);
    }
}
