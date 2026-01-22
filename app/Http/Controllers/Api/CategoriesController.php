<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * List categories for authenticated restaurant
     */
    public function index(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $restCates = $restaurant->categories()->orderBy('name')->get();
        if (count($restCates) >= 0) {
            return response()->json(
                response()->json([
                    'data' => $restCates,
                ])
            );
        } else {
            return response()->json([
                'Message' => 'No Categories for this restaurant!',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Create a new category
     */
    public function store(Request $request)
    {
        $restaurant = $request->user()->restaurant;

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'categories', $filename, 'public'
            );
        }

        $category = Category::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'image'         => $validated['image'],
            'is_active'     => $validated['is_active'],
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data'    => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Category $category)
    {
        $restaurant = $request->user()->restaurant;

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image'       => 'sometimes|nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $file = $request->file('image');
            $filename = $category->name . rand(1,9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $filename, 'public');

            $validated['image'] = $path;
        } else {
            $path = $category->image;
        }

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'data'    => $category->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'Message' => 'Category Deleted Successfully.',
        ]);
    }
}
