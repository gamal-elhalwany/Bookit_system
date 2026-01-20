<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\Comment;
use App\Models\RestaurantImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'image' => 'required|string|max:255',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'rate' => 'numeric|min:0|max:9.99',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'business_type' => 'required|in:restaurant,cafe',
            'subscription_end_date' => 'nullable|date|after_or_equal:today',
        ]);


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
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
            'image' => 'sometimes|required|string|max:255',
            'opening_time' => 'sometimes|required|date_format:H:i',
            'closing_time' => 'sometimes|required|date_format:H:i|after:opening_time',
            'rate' => 'sometimes|numeric|min:0|max:9.99',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'business_type' => 'sometimes|required|in:restaurant,cafe',
            'subscription_end_date' => 'nullable|date|after_or_equal:today',
        ]);

        $restaurant->update($validated);

        return response()->json([
            'message' => 'Restaurant updated successfully',
            'data' => $restaurant
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
