<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $products = Product::where('restaurant_id', $restaurantId)->get();

        if (count($products)) {
            return response()->json([
                'Data' => $products,
            ], 201);
        }
        return response()->json([
            'message' => 'No products for this restaurant!',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255|unique:products,name->ar',
            'name.en' => 'required|string|max:255|unique:products,name->en',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string|max:2000',
            'description.en' => 'nullable|string|max:2000',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'products', $filename, 'public'
            );
        }

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'data'    => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $restaurantId = $request->query('restaurant_id');
        $product = Product::where('restaurant_id', $restaurantId)
        ->where('id', $product->id)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found for this restaurant!',
            ], 404);
        }

        return response()->json([
            'Product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'sometimes|array',
            'name.ar' => 'sometimes|string|max:255|unique:products,name->ar',
            'name.en' => 'sometimes|string|max:255|unique:products,name->en',
            'description' => 'nullable|array',
            'description.ar' => 'nullable|string|max:2000',
            'description.en' => 'nullable|string|max:2000',
            'category_id' => 'required|exists:categories,id',
            'price' => 'sometimes|numeric',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $file = $request->file('image');
            $filename = Carbon::now()->timestamp . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');

            $validated['image'] = $path;
        } else {
            $path = $product->image;
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => $product,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product has been deleted successfully.',
            'Product' => $product,
        ]);
    }
}
