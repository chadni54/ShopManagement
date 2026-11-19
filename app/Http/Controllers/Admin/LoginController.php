<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ShopApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private ShopApi $api) {}

    public function create(): View|RedirectResponse
    {
        if (session('api_token')) {
            return redirect()->route('dashboard');
        }

        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $result = $this->api->post('login', [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'device_name' => 'dashboard',
        ]);

        if (! $result->successful || ! is_array($result->data) || empty($result->data['token'])) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', $result->message ?: 'Login failed.')
                ->with('api_errors', $result->errors);
        }

        $request->session()->regenerate();
        $request->session()->put('api_token', $result->data['token']);
        $request->session()->put('api_user', $result->data['user'] ?? []);

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($request->session()->has('api_token')) {
            $this->api->post('logout');
        }

        $request->session()->forget(['api_token', 'api_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
