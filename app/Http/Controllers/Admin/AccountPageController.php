<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ApiResult;
use App\Admin\ShopApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JsonException;

class AccountPageController extends Controller
{
    public function __construct(private ShopApi $api) {}

    public function settings(): View|RedirectResponse
    {
        $result = $this->api->get('settings');

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return view('admin.settings', [
            'setting' => $result->record(),
            'error' => $result->successful ? null : ($result->message ?: 'Could not load settings.'),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $payload = [
            'enable_notifications' => $request->boolean('enable_notifications'),
            'enable_email' => $request->boolean('enable_email'),
            'enable_push' => $request->boolean('enable_push'),
            'language' => $request->string('language')->toString(),
            'timezone' => $request->string('timezone')->toString(),
            'currency' => $request->string('currency')->toString(),
        ];

        $preferences = trim($request->string('preferences')->toString());

        if ($preferences !== '') {
            try {
                $decoded = json_decode($preferences, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return back()->withInput()->with('error', 'Preferences must be valid JSON.');
            }

            if (! is_array($decoded)) {
                return back()->withInput()->with('error', 'Preferences must be a JSON object.');
            }

            $payload['preferences'] = $decoded;
        }

        $result = $this->api->put('settings', $payload);

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        return redirect()->route('admin.settings')->with('success', $result->message ?: 'Settings updated successfully');
    }

    public function notifications(): View|RedirectResponse
    {
        $result = $this->api->get('notifications', ['per_page' => 50]);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return view('admin.notifications', [
            'rows' => $result->rows(),
            'error' => $result->successful ? null : ($result->message ?: 'Could not load notifications.'),
        ]);
    }

    public function markRead(string $id): RedirectResponse
    {
        $result = $this->api->post('notifications/'.$id.'/read');

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        return back()->with('success', $result->message ?: 'Notification marked as read');
    }

    public function markAllRead(): RedirectResponse
    {
        $result = $this->api->post('notifications/read-all');

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        return back()->with('success', $result->message ?: 'All notifications marked as read');
    }

    public function profile(): View|RedirectResponse
    {
        $result = $this->api->get('profile');

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return view('admin.profile', [
            'profile' => $result->record(),
            'error' => $result->successful ? null : ($result->message ?: 'Could not load profile.'),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $payload = array_filter([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $request->filled('password') ? $request->input('password') : null,
        ], fn ($value) => $value !== null && $value !== '');

        $result = $this->api->put('profile', $payload);

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        if (is_array($result->data)) {
            $request->session()->put('api_user', $result->data);
        }

        return redirect()->route('admin.profile')->with('success', $result->message ?: 'Profile updated');
    }

    private function expired(ApiResult $result): ?RedirectResponse
    {
        if ($result->status !== 401) {
            return null;
        }

        session()->forget(['api_token', 'api_user']);

        return redirect()->route('login')->with('error', 'Your session expired. Log in again.');
    }

    private function backWithError(ApiResult $result): RedirectResponse
    {
        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return back()
            ->withInput()
            ->with('error', $result->message ?: 'The API rejected this request.')
            ->with('api_errors', $result->errors);
    }
}
