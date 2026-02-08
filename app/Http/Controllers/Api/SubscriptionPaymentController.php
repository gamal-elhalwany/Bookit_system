<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionsPayments;
use App\Services\SubscriptionService;

class SubscriptionPaymentController extends Controller
{
    /**
     * Handle Paymob webhook for payment status.
     */
    public function handleWebhook(Request $request)
    {
        // Paymob بتبعت بيانات كتير، أهمها hmac للتأمين و success
        $success = $request->query('success');
        $orderId = $request->query('order');

        if ($success === 'true') {
            // البحث عن سجل الدفع باستخدام الـ Order ID اللي خزنناه
            $payment = SubscriptionsPayments::where('transaction_id', $orderId)->first();

            if ($payment && $payment->status !== 'completed') {
                $payment->update(['status' => 'completed', 'payload' => json_encode($request->all())]);

                // Active the subscription linked to this payment immediately
                $service = new SubscriptionService();
                $service->markAsPaid($payment->subscription_id);

                return response()->json(['message' => 'Payment Successful'], 200);
            }
        }

        return response()->json(['message' => 'Payment Failed'], 400);
    }
}
