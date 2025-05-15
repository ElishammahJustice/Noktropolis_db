<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;

class VendorProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('view_vendor_products')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(Product::where('vendor_id', $user->id)->get());
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('add_product')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
        ]);

        $product = Product::create([
            ...$validated,
            'vendor_id' => $user->id,
        ]);

        return response()->json(['message' => 'Product created', 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('edit_product')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $product = Product::where('vendor_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|required|numeric',
            'stock'       => 'sometimes|required|integer',
        ]);

        $product->update($validated);

        return response()->json(['message' => 'Product updated', 'product' => $product]);
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->hasAbility('delete_product')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $product = Product::where('vendor_id', $user->id)->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
