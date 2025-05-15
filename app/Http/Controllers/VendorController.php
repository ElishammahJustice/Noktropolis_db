<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Vendor’s orders
    public function getOrders()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_vendor_orders')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        return response()->json(Order::where('vendor_id', $user->id)->get());
    }

    // Update order status
    public function updateOrderStatus(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('update_order_status')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $order = Order::where('vendor_id', $user->id)->findOrFail($id);
        $order->update(['status' => $request->status]);
        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }

    // Get earnings
    public function getEarnings()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_earnings')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $total = Order::where('vendor_id', $user->id)->sum('total_price');
        return response()->json(['total_earnings' => $total]);
    }

    // Get store details
    public function getStoreDetails()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_store_settings')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json([
            'store_name'        => $user->store_name,
            'store_description' => $user->store_description,
            'contact_email'     => $user->email,
            'contact_phone'     => $user->phone,
            'store_logo'        => $user->store_logo,
            'store_status'      => $user->is_approved ? 'Active' : 'Pending Approval',
        ]);
    }

    // Update store details
    public function updateStoreDetails(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('edit_store_settings')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'store_name'        => 'required|string|max:255',
            'store_description' => 'nullable|string',
        ]);

        $user->update($validated);

        return response()->json(['message' => 'Store details updated successfully', 'vendor' => $user]);
    }
}
