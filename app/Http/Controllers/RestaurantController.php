<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RestaurantImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{

    /**
     * Get all restaurants.
     */
    public function index()
    {
        $restaurants = Restaurant::with('subscription')->get();

        return response()->json($restaurants);
    }

    /**
     * Create a new restaurant.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:restaurants,name',
            'address' => 'required|string|max:255|unique:restaurants,address',
            'phone' => 'required|string',
            'email' => 'required|email|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,webp',
            'opening_time' => 'nullable',
            'closing_time' => 'required|after:opening_time',
            'rate' => 'numeric|min:0|max:9.99',
            'subscription_id' => 'required|exists:subscriptions,id',
            'business_type' => 'required|in:restaurant,cafe',
            'subscription_end_date' => 'nullable|date|after_or_equal:today',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'restaurants',
                $filename,
                'public'
            );
        }

        $validated['user_id'] = Auth::guard('api')->id();
        $restaurant = Restaurant::create($validated);

        return response()->json([
            'message' => 'Restaurant created successfully',
            'data' => $restaurant
        ], 201);
    }

    /**
     * Show a specific restaurant.
     */
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return response()->json($restaurant);
    }

    /**
     * Update an existing restaurant.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|exists:users,id',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'image' => 'sometimes|string|max:255',
            'opening_time' => 'sometimes|date_format:H:i',

            'closing_time' => [
                'sometimes',
                'date_format:H:i',
                'after:' . ($request->opening_time ?? $restaurant->opening_time)
            ],

            'rate' => 'sometimes|numeric|min:0|max:9.99',
            'subscription_id' => 'sometimes|nullable|exists:subscriptions,id',
            'business_type' => 'sometimes|in:restaurant,cafe',
            'subscription_end_date' => 'sometimes|nullable|date|after_or_equal:today',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }

            $file = $request->file('image');
            $filename = $restaurant->name . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('categories', $filename, 'public');

            $validated['image'] = $path;
        } else {
            $path = $restaurant->image;
        }

        $restaurant->update($validated);

        return response()->json([
            'message' => 'Restaurant updated successfully',
            'data' => $restaurant->fresh(),
        ]);
    }

    /**
     * Delete a restaurant.
     */
    public function delete($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(['message' => 'Restaurant deleted successfully']);
    }


    /**
     * Add an image to a restaurant.
     */
    public function storerestimage(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'image' => 'required|image|mimes:jpg,png,jpeg,webp',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'restaurants',
                $filename,
                'public'
            );
        }

        $image = RestaurantImage::create([
            'restaurant_id' => $request->restaurant_id,
            'image' => $validated['image'],
        ]);

        return response()->json([
            'message' => 'Image added successfully',
            'data' => $image
        ], 201);
    }


    /**
     * Get all restaurants images.
     */
    public function getrestimages($id)
    {
        $images = RestaurantImage::where('restaurant_id', $id)->get();
        return response()->json([
            'Images' => $images
        ]);
    }

    /**
     * Delete a restaurant image.
     */
    public function deleterestimage($id)
    {
        $image = RestaurantImage::findOrFail($id);

        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully.'
        ]);
    }
}
