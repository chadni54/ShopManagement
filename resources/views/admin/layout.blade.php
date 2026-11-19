<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} · ShopDemo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|newsreader:500,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <strong>ShopDemo</strong>
            <span>Admin</span>
        </a>
        <div class="nav-groups">
            @foreach (\App\Admin\ResourceCatalog::navigation() as $group)
                <div class="nav-group">
                    <div class="nav-label">{{ $group['label'] }}</div>
                    @foreach ($group['items'] as $item)
                        @php
                            $active = ($item['route'] ?? null) === 'dashboard'
                                ? request()->routeIs('dashboard')
                                : request()->is($item['pattern']);
                        @endphp
                        <a class="nav-link {{ $active ? 'active' : '' }}" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="sidebar-foot">
            @php $user = session('api_user', []); @endphp
            <div class="who">
                {{ $user['name'] ?? 'Signed in' }}
                <small>{{ $user['email'] ?? '' }}</small>
            </div>
            <a class="doc-link" href="{{ url('/api/documentation') }}">API documentation</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-ghost" type="submit">Log out</button>
            </form>
        </div>
    </aside>
    <main class="main">
        @if (session('success'))
            <div class="flash flash-ok">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash-bad">{{ session('error') }}</div>
        @endif
        @if ($apiErrors = session('api_errors'))
            <div class="flash flash-bad">
                <ul>
                    @foreach ($apiErrors as $messages)
                        @foreach ((array) $messages as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
