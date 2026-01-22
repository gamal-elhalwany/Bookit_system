<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\Comment;
use App\Models\RestaurantImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{

    // عرض كل المطاعم
    public function index()
    {
        $restaurants = Restaurant::with('subscription')->get();

        return response()->json($restaurants);
    }


    // إضافة مطعم جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:restaurants,name',
            'address' => 'required|string|max:255|unique:restaurants,address',
            'phone' => 'required|string',
            'email' => 'required|email|max:255',
            'image' => 'required|image|mimes:jpg,png,jpeg,webp',
            'opening_time' => 'required',
            'closing_time' => 'required|after:opening_time',
            'rate' => 'numeric|min:0|max:9.99',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'business_type' => 'required|in:restaurant,cafe',
            'subscription_end_date' => 'nullable|date|after_or_equal:today',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $file = $request->file('image');

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $validated['image'] = $file->storeAs(
                'restaurants', $filename, 'public'
            );
        }

        $validated['user_id'] = Auth::guard('api')->id();
        $restaurant = Restaurant::create($validated);

        return response()->json([
            'message' => 'Restaurant created successfully',
            'data' => $restaurant
        ], 201);
    }




    // عرض مطعم واحد
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return response()->json($restaurant);
    }

    // تعديل بيانات مطعم
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

    // حذف مطعم
    public function delete($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->delete();

        return response()->json(['message' => 'Restaurant deleted successfully']);
    }


/////اضافة صوره للمطعم
    public function storerestimage(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'image' => 'required|string',
        ]);

        $image = RestaurantImage::create([
            'restaurant_id' => $request->restaurant_id,
            'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'Image added successfully',
            'data' => $image
        ], 201);
    }


//////////عرض صور المطعم
    public function getrestimages($restaurant_id)
    {
        $images = RestaurantImage::where('restaurant_id', $restaurant_id)->get();
        return response()->json($images);
    }

    public function updaterestimage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $image = RestaurantImage::findOrFail($id);

        $image->update([
            'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'Image updated successfully',
            'data' => $image
        ]);
    }
/////حذف صوره مطعم
    public function deleterestimage($id)
    {
        $image = RestaurantImage::findOrFail($id);

        if ($image->image && \Storage::disk('public')->exists($image->image)) {
            \Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json([
            'message' => 'Image deleted successfully'
        ]);
    }
/////كل المطاعم الخاصه بالشخص الي مسجل دخول
public function myRestaurants()
{
    $userId = Auth::id();

    $restaurants = Restaurant::where('user_id', $userId)->get();

    return response()->json([
        'message' => 'My restaurants retrieved successfully',
        'data' => $restaurants
    ], 200);
}






}
