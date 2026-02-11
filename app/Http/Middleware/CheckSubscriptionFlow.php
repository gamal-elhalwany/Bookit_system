<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionFlow
{
    public function handle(Request $request, Closure $next): Response
    {
        // 7|KMl50mNyZiyCvQRVliFvwMbEXyaGaelNbM5o9Qgee936d0f8
        $user = auth()->user();

        // 1. Admin Bypass (صلاحية كاملة للأدمن)
        // if ($user->hasRole('admin')) { 
        //     return $next($request);
        // }
        
        // 2. دمج الكود الأول: التحقق من وجود الـ ID (Header أو Body)
        // استخدمت $request->input() لأنها أشمل من $request->restaurant_id
        $restaurantId = $request->header('X-Restaurant-Id') ?? $request->input('restaurant_id')
            ?? $request->route('restaurant')  // لو الـ URL فيه {restaurant}
            ?? $request->route('id');

        // 2. التحقق من أن القيمة ليست كائن (Object)
        // أحياناً لارافيل بيحول الـ ID لـ Model تلقائياً، إحنا محتاجين الـ ID بس
        if (is_object($restaurantId)) {
            $restaurantId = $restaurantId->id;
        }

        if (!$restaurantId) {
            return response()->json([
                'step' => 'select_restaurant',
                'message' => 'برجاء تحديد المطعم.'
            ], 403);
        }

        // 3. دمج الكود الثاني: التحقق من الملكية + جلب البيانات في خطوة واحدة
        // هنا: تأكدنا إنه يخص المستخدم وجبنا الموديل عشان نفحص الاشتراك
        $restaurant = $user->restaurants()->where('restaurants.id', $restaurantId)->first();

        if (!$restaurant) {
            return response()->json([
                'step' => 'unauthorized',
                'message' => 'ليس لديك صلاحية الوصول لهذا المطعم أو المطعم غير موجود.'
            ], 403);
        }

        // 4. فحص الاشتراك (وظيفة الميدلوير الأساسية)
        // لاحظ هنا استخدمنا العلاقة من الـ $restaurant اللي لسه جايبينه فوق
        $activeSubscription = $restaurant->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->exists();

        if (!$activeSubscription && $restaurant->subscriptions()->exists()) {
            return response()->json([
                'step' => 'payment_required',
                'restaurant_id' => $restaurant->id,
                'message' => 'اشتراك المطعم منتهي أو غير فعال، برجاء الدفع للتجديد او التفعيل.'
            ], 402);
        }

        // 5. تمرير الكائن للمستقبل (عشان متعملش Query تاني في الكنترولر)
        $request->merge(['current_restaurant' => $restaurant]);

        return $next($request);
    }
}
