<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    // Get Vendor Orders
    public function getOrders()
    {
        return response()->json(Order::where('vendor_id', Auth::id())->get());
    }

    // Update Order Status
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::where('vendor_id', Auth::id())->findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }

    // Get Earnings
    public function getEarnings()
    {
        $totalEarnings = Order::where('vendor_id', Auth::id())->sum('total_price');

        return response()->json(['total_earnings' => $totalEarnings]);
    }

    // Get Store Details
    public function getStoreDetails()
    {
        $vendor = Auth::user();

        return response()->json([
            'store_name' => $vendor->store_name,
            'store_description' => $vendor->store_description,
            'contact_email' => $vendor->email,
            'contact_phone' => $vendor->phone ?? null,
            'store_logo' => $vendor->store_logo ?? null,
            'store_status' => $vendor->is_approved ? 'Active' : 'Pending Approval'
        ]);
    }

    
   // Update Store Details
public function updateStoreDetails(Request $request)
{
    $vendor = Auth::user();

    $validatedData = $request->validate([
        'store_name' => 'required|string|max:255',
        'store_description' => 'nullable|string',
    ]);

    // Directly update the user record
    $vendor->update($validatedData);

    return response()->json(['message' => 'Store details updated successfully', 'vendor' => $vendor]);
}
}
