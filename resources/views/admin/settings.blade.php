@extends('admin.layout')

@section('content')
    @php
        $preferences = old('preferences', $setting['preferences'] ?? null);
        if (is_array($preferences)) {
            $preferences = json_encode($preferences, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $flag = function (string $key) use ($setting): string {
            $value = old($key, $setting[$key] ?? false);
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        };
    @endphp

    <div class="top">
        <div>
            <h1>Settings</h1>
            <p>GET / PUT /api/v1/settings</p>
        </div>
    </div>

    @if ($error)
        <div class="flash flash-bad">{{ $error }}</div>
    @endif

    <form class="card" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <div class="grid-2">
            @foreach (['enable_notifications' => 'Notifications', 'enable_email' => 'Email', 'enable_push' => 'Push'] as $name => $label)
                <div class="field">
                    <label for="{{ $name }}">{{ $label }}</label>
                    <select id="{{ $name }}" name="{{ $name }}">
                        <option value="1" @selected($flag($name) === '1')>Yes</option>
                        <option value="0" @selected($flag($name) === '0')>No</option>
                    </select>
                </div>
            @endforeach
            <div class="field">
                <label for="language">Language</label>
                <input id="language" name="language" value="{{ old('language', $setting['language'] ?? '') }}" maxlength="10">
            </div>
            <div class="field">
                <label for="timezone">Timezone</label>
                <input id="timezone" name="timezone" value="{{ old('timezone', $setting['timezone'] ?? '') }}" maxlength="64">
            </div>
            <div class="field">
                <label for="currency">Currency</label>
                <input id="currency" name="currency" value="{{ old('currency', $setting['currency'] ?? '') }}" maxlength="3">
            </div>
            <div class="field" style="grid-column: 1 / -1">
                <label for="preferences">Preferences (JSON)</label>
                <textarea id="preferences" name="preferences">{{ $preferences }}</textarea>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Save settings</button>
    </form>
@endsection
