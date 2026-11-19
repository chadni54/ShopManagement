<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in · ShopDemo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|newsreader:500,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
</head>
<body class="login-body">
    <form class="card login-card" method="POST" action="{{ route('login') }}">
        @csrf
        <h1>ShopDemo</h1>
        <p class="hint">Sign in with the API. Demo admin: admin@shopdemo.test / password</p>
        @if (session('error'))
            <div class="flash flash-bad">{{ session('error') }}</div>
        @endif
        @if ($apiErrors = session('api_errors'))
            <div class="flash flash-bad">
                @foreach ($apiErrors as $messages)
                    @foreach ((array) $messages as $message)
                        <div>{{ $message }}</div>
                    @endforeach
                @endforeach
            </div>
        @endif
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>
        <button class="btn btn-primary" type="submit">Log in</button>
    </form>
</body>
</html>
