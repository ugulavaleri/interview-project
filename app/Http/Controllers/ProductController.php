<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => ['required','string'],
            'price' => ['required'],
        ]);

        Product::create($validatedData);

        return response()->json([
            'success' => 'product created successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update-product',$product);
        $validatedData = $request->validate([
            'product_name' => ['required','string'],
            'price' => ['required'],
        ]);

        $product->update($validatedData);

        return response()->json([
            'success' => 'product updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete-product',$product);
        $product->delete();
        return response()->json([
            'success' => 'product removed successfully!'
        ]);
    }
}
