<?php

use App\Domains\Auth\Controllers\AuthController;
use App\Domains\Brands\Controllers\BrandController;
use App\Domains\Categories\Controllers\CategoryController;
use App\Domains\Coupons\Controllers\CouponController;
use App\Domains\Customers\Controllers\CustomerController;
use App\Domains\Inventory\Controllers\InventoryItemController;
use App\Domains\Invoices\Controllers\InvoiceController;
use App\Domains\Notifications\Controllers\NotificationController;
use App\Domains\Orders\Controllers\OrderController;
use App\Domains\Payments\Controllers\PaymentController;
use App\Domains\Products\Controllers\ProductController;
use App\Domains\Reviews\Controllers\ReviewController;
use App\Domains\Settings\Controllers\SettingController;
use App\Domains\Shipping\Controllers\ShipmentController;
use App\Domains\Support\Controllers\AmsSupportReplyController;
use App\Domains\Support\Controllers\SupportSmsController;
use App\Domains\Users\Controllers\UserController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', HealthController::class);

    Route::post('/support/sms', [SupportSmsController::class, 'store']);
    Route::post('/ams/support-replies', AmsSupportReplyController::class);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);

        Route::apiResource('users', UserController::class);
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('products', ProductController::class);

        Route::apiResource('inventory', InventoryItemController::class)->parameters([
            'inventory' => 'inventory',
        ]);

        Route::apiResource('coupons', CouponController::class);

        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
        Route::apiResource('orders', OrderController::class);

        Route::apiResource('payments', PaymentController::class);
        Route::apiResource('shipping', ShipmentController::class)->parameters([
            'shipping' => 'shipping',
        ]);
        Route::apiResource('invoices', InvoiceController::class);
        Route::apiResource('reviews', ReviewController::class);

        Route::get('/settings', [SettingController::class, 'show']);
        Route::put('/settings', [SettingController::class, 'update']);

        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    });
});
