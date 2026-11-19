<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ApiResult;
use App\Admin\ShopApi;
use App\Domains\Support\Models\SupportSmsMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private ShopApi $api) {}

    public function index(): View|RedirectResponse
    {
        $health = $this->api->get('health');
        $orders = $this->api->get('orders', ['per_page' => 5]);
        $customers = $this->api->get('customers', ['per_page' => 1]);
        $products = $this->api->get('products', ['per_page' => 1]);
        $payments = $this->api->get('payments', ['per_page' => 1]);
        $notifications = $this->api->get('notifications', ['per_page' => 5]);

        if ($redirect = $this->expired($orders, $customers, $products, $payments, $notifications)) {
            return $redirect;
        }

        return view('admin.dashboard', [
            'health' => $health->record(),
            'healthOk' => $health->successful,
            'orders' => $orders->rows(),
            'orderCount' => (int) ($orders->meta['total'] ?? 0),
            'customerCount' => (int) ($customers->meta['total'] ?? 0),
            'productCount' => (int) ($products->meta['total'] ?? 0),
            'paymentCount' => (int) ($payments->meta['total'] ?? 0),
            'notifications' => $notifications->rows(),
            'notificationCount' => (int) ($notifications->meta['total'] ?? 0),
            'smsMessages' => SupportSmsMessage::query()->latest('id')->limit(8)->get(),
            'smsCount' => SupportSmsMessage::query()->count(),
        ]);
    }

    private function expired(ApiResult ...$results): ?RedirectResponse
    {
        foreach ($results as $result) {
            if ($result->status === 401) {
                session()->forget(['api_token', 'api_user']);

                return redirect()->route('login')->with('error', 'Your session expired. Log in again.');
            }
        }

        return null;
    }
}
