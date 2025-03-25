<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    // Get products for the authenticated vendor
    public function index()
    {
        return response()->json(Product::where('vendor_id', Auth::id())->get());
    }

    // Create a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $request->image,
            'vendor_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    // Update a product (only if it belongs to the vendor)
    public function update(Request $request, $id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    // Delete a product (only if it belongs to the vendor)
    public function destroy($id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
