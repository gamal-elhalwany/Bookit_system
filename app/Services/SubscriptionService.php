<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\SubscriptionsPayments;

class SubscriptionService
{
    /**
     * Check if the restaurant has an active subscription.
     */
    public function hasActiveSubscription($restaurantId)
    {
        return Subscription::where('restaurant_id', $restaurantId)
            ->where('status', 'active')
            ->where('ends_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Activate or create a new subscription for a restaurant.
     */
    public function processSubscription($restaurantId, $packageId)
    {
        // 1. Get the package details so we can find out the duration.(duration_days)
        $package = Package::findOrFail($packageId);

        // 2. بنستخدم updateOrCreate عشان لو العملية انقطعت قبل كدة 
        // نبحث عن اشتراك "قيد الانتظار" لنفس المحل ونفس الباقة ونحدثه بدل ما نكرر السجلات
        return Subscription::updateOrCreate(
            [
                'restaurant_id' => $restaurantId,
                'package_id'    => $packageId,
                'status'        => 'pending',
            ],
            [
                'starts_at' => Carbon::now(),
                'ends_at'   => Carbon::now()->addDays($package->duration_days),
                // بنسيب الـ status زي ما هي pending لحد ما الدفع ينجح فعلياً
            ]
        );
    }

    /**
     * Create a payment record for the subscription.
     */
    public function createPaymentRecord($subscription, $amount, $method)
    {
        $user = Auth::user();

        return SubscriptionsPayments::updateOrCreate([
            'restaurant_id'   => $subscription->restaurant_id,
        ], [
            'subscription_id' => $subscription->id,
            'amount'          => $amount,
            'payment_method'  => $method, // مثلاً 'card' أو 'Wallet'
            'payment_gateway' => 'paymob',
            'payer_email'     => $user->email,
            'status'          => 'pending',
            'payload'         => json_encode(['initial_request' => true]), // قيمة مبدئية بدل null
            'transaction_id'  => bin2hex(random_bytes(10)), // كود مؤقت لحد ما يجي الـ ID الحقيقي
        ]);
    }

    /**
     * Get Paymob payment link for the subscription.
     */
    public function getPaymobLink($subscription, $amount, $paymentMethod, $walletNumber = null)x
    {
        // المرحلة 1: الحصول على Authentication Token
        $auth = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);
        $token = $auth->json()['token'];

        // المرحلة 2: تسجيل Order في Paymob
        $order = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => $amount * 100, // تحويل القرش
            'currency' => 'EGP',
            'items' => []
        ]);
        $orderId = $order->json()['id'];

        // المرحلة 3: إنشاء الـ Payment Key (هنا بنختار الـ ID بناءً على الوسيلة)
        $integrationId = ($paymentMethod === 'card') 
            ? env('PAYMOB_CARD_INTEGRATION_ID') 
            : env('PAYMOB_WALLET_INTEGRATION_ID');

        // المرحلة 3: الحصول على Payment Key (هنا بنربط الاشتراك بالدفع)
        $paymentKey = Http::withOptions([
            'verify' => false,
        ])->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $amount * 100,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'first_name' => 'Restaurant',
                'last_name' => 'Owner',
                'email' => 'test@test.com',
                'phone_number' => '01000000000',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'NA',
                'state' => 'NA'
            ],
            'currency' => 'EGP',
            // 'integration_id' => 5497522 // Integration ID for Card Payments
            'integration_id' => $integrationId // Integration ID for Wallet Payments
        ]);

        // نحدث سجل الدفع بالـ Order ID بتاع Paymob عشان نعرفه لما يرجع
        $subscription->payments()->latest()->first()->update([
            'transaction_id' => $orderId
        ]);

        // return 'https://accept.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentKey->json()['token'];

        if ($paymentMethod === 'card') {
            // الكارت بيرجع رابط Iframe
            return 'https://accept.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentKey->json()['token'];
        } else {
            // المحفظة لازم "تضرب" API تاني عشان تجيب رابط الـ Redirection
            $walletResponse = Http::post('https://accept.paymob.com/api/acceptance/payments/pay', [
                'source' => [
                    'identifier' => '01010101010', // هنا المفروض رقم محفظة العميل اللي هيدفعه
                    'subtype' => 'WALLET'
                ],
                'payment_token' => $paymentKey->json()['token']
            ]);

            // المحفظة بترجع رابط مباشر لشركة الاتصالات (فودافون كاش مثلا)
            return $walletResponse->json()['iframe_redirection_url'];
        }
    }

    /**
     * Called when payment is confirmed to mark subscription as paid.
     */
    public function markAsPaid($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $subscription->update(['status' => 'active']);

        return $subscription;
    }
}
