<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index','show']);
    }

    // Public: list all products
    public function index()
    {
        return response()->json(Product::all());
    }

    // Public: show a single product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // Admin-only or vendor-only product creation/updating would go in VendorProductController
}
