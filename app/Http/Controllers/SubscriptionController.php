<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;

use Illuminate\Http\Request;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    public function stats()
    {
        return response()->json([
            'message' => 'Subscription stats endpoint - Your subscription is active.'
        ], 200);
    }
    /**
     * Checkout and process subscription payment.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'package_id'    => 'required|exists:packages,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'payment_method'=> 'required|string',
            'wallet_number' => 'required_if:payment_method,wallet|digits:11',
        ]);

        // 1. التأكد من ملكية المطعم
        $restaurant = $request->user()->restaurants()
            ->where('restaurnt_users.restaurant_id', $request->restaurant_id)
            ->firstOrFail();

        $service = new SubscriptionService();

        $package = Package::find($request->package_id);

        // 2. التحقق من وجود اشتراك فعال (اللوجيك الجديد)
        if ($service->hasActiveSubscription($restaurant->id)) {
            return response()->json([
                'message' => 'هذا المطعم لديه اشتراك فعال بالفعل. لا يمكنك التجديد الآن.'
            ], 400);
        }

        // 3. لو مفيش اشتراك، نكمل العملية عادي
        $subscription = $service->processSubscription(
            $restaurant->id,
            $package->id,
        );

        // 4. إنشاء سجل المدفوعات وربطه بالاشتراك
        $payment = $service->createPaymentRecord(
            $subscription, 
            $package->price, 
            $request->payment_method
        );

        // 5. الحصول على رابط الدفع من Paymob
        $paymentUrl = $service->getPaymobLink($subscription, $package->price, $request->payment_method, $request->wallet_number);

        return response()->json([
            'message'         => 'Payment initialized',
            'subscription_id' => $subscription->id,
            'restaurant_id'   => $restaurant->id,
            'payment_id'      => $payment->id,
            'amount'          => $payment->amount,
            'transaction_id'  => $payment->transaction_id,
            'wallet_number'   => $request->wallet_number ?? null,
            'payment_url'     => $paymentUrl,
        ]);
    }

    /**
     * Get all subscriptions.
     */
    public function index()
    {
        $subscriptions = Subscription::all();
        if ($subscriptions->isEmpty()) {
            return response()->json([
                'message' => 'No subscriptions found',
                'data'    => []
            ], 200);
        }
        return response()->json([
            'message' => 'Subscriptions retrieved successfully',
            'data'    => $subscriptions
        ], 200);
    }

    /**
     * Get a specific subscription by ID.
     */
    public function show($id)
    {
        $subscription = Subscription::findOrFail($id);
        if (!$subscription) {
            return response()->json([
                'message' => 'Subscription not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Subscription retrieved successfully',
            'data'    => $subscription
        ], 200);
    }

    /**
     * Create a new subscription.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update an existing subscription.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete a subscription.
     */
    public function delete()
    {
        return response()->json([
            'message' => 'This Action Can not be Allowed. Subscriptions are managed automatically based on payment status and package duration.'
        ], 403);
    }
}
