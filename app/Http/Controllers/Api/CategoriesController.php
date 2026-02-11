<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * List categories for authenticated restaurant
     */
    public function index(Request $request)
    {
        $category = Category::where('restaurant_id', $request->header('X-Restaurant-Id') ?? $request->input('restaurant_id')
            ?? $request->route('restaurant')
            ?? $request->route('id'))->get();

        if (!empty($category)) {
            return response()->json([
                    'data' => $category,
            ]);
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
        // $user = auth()->user();

        // $authorized = $user->restaurants()
        //     ->where('restaurants.id', $request->restaurant_id)
        //     ->exists();

        // if (!$authorized) {
        //     return response()->json([
        //         'message' => 'Unauthorized restaurant'
        //     ], 403);
        // }

        $validated = $request->validate([
            'restaurant_id'     => 'required|exists:restaurants,id',
            'name'              => 'required|array',
            'name.ar'           => ['required', 'string', 'max:255', Rule::unique('categories', 'name->ar')],
            'name.en'           => ['required', 'string', 'max:255', Rule::unique('categories', 'name->en')],
            'description'       => 'nullable|array',
            'description.ar'    => 'nullable|string|min:3|max:1000',
            'description.en'    => 'nullable|string|min:3|max:1000',
            'image'             => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'is_active'         => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'categories',
                $filename,
                'public'
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
        $restaurant = auth()->user()->restaurant;

        $validated = $request->validate([
            'restaurant_id'     => 'required|exists:restaurants,id',
            'name'              => 'required|array',
            'name.ar'           => ['required', 'string', 'max:255', Rule::unique('categories', 'name->ar')],
            'name.en'           => ['required', 'string', 'max:255', Rule::unique('categories', 'name->en')],
            'description'       => 'nullable|array',
            'description.ar'    => 'nullable|string|min:3|max:1000',
            'description.en'    => 'nullable|string|min:3|max:1000',
            'image'             => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'is_active'         => 'boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $file = $request->file('image');
            $filename = Carbon::now()->timestamp . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
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
    public function destroy(Request $request, Category $category)
    {
        $user = auth()->user();
        $restaurantId = $request->header('X-Restaurant-Id') ?? $request->input('restaurant_id')
            ?? $request->route('restaurant')
            ?? $request->route('id');

        $restaurant = $user->restaurants()->where('restaurants.id', $restaurantId)->first();

         if ($category->restaurant_id !== $restaurant->id) {
            return response()->json([
                'message' => 'Unauthorized category'
            ], 403);
        }

         if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return response()->json([
            'Message' => 'Category Deleted Successfully.',
        ]);
    }
}
