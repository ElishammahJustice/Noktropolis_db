<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Get all products (for customers)
    public function index()
    {
        return response()->json(Product::all());
    }

    // Get a single product (for customers)
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
}
