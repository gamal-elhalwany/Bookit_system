<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    // عرض كل الباقات
    public function index()
    {
        $subscriptions = Subscription::all();
        return response()->json([
            'message' => 'Subscriptions retrieved successfully',
            'data'    => $subscriptions
        ], 200);
    }

    // عرض باقة واحدة
    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        return response()->json([
            'message' => 'Subscription retrieved successfully',
            'data'    => $subscription
        ], 200);
    }

    // إنشاء باقة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        $subscription = Subscription::create($validated);

        return response()->json([
            'message' => 'Subscription created successfully',
            'data'    => $subscription
        ], 201);
    }

    // تعديل باقة
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->update($validated);

        return response()->json([
            'message' => 'Subscription updated successfully',
            'data'    => $subscription
        ], 200);
    }

    // حذف باقة
    public function delete($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return response()->json([
            'message' => 'Subscription deleted successfully'
        ], 200);
    }
}
