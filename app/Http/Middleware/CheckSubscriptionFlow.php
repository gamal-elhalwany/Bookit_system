<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFlow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Admin Bypass
        if ($user->hasRole('admin')) { 
            return $next($request);
        }

        // 2. Receive Restaurant ID
        $restaurantId = $request->header('X-Restaurant-Id') ?? $request->restaurant_id;

        if (!$restaurantId) {
            return response()->json([
                'step' => 'select_restaurant',
                'message' => 'برجاء تحديد المطعم.'
            ], 403);
        }

        // 3. Check if the restaurant belongs to the user
        $restaurant = $user->restaurants()->where('restaurants.id', $restaurantId)->first();

        if (!$restaurant) {
            return response()->json([
                'step' => 'unauthorized',
                'message' => 'ليس لديك صلاحية الوصول لهذا المطعم.'
            ], 403);
        }

        // 4. Check for active subscription
        $activeSubscription = $restaurant->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();

        if (!$activeSubscription) {
            return response()->json([
                'step' => 'payment_required',
                'restaurant_id' => $restaurant->id,
                'message' => 'اشتراك المطعم منتهي أو غير فعال.'
            ], 403);
        }

        // 5. Attach restaurant to request for further processing
        $request->merge(['current_restaurant' => $restaurant]);

        return $next($request);
    }
}
