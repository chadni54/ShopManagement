@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>Profile</h1>
            <p>GET / PUT /api/v1/profile</p>
        </div>
    </div>

    @if ($error)
        <div class="flash flash-bad">{{ $error }}</div>
    @endif

    <form class="card" method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="grid-2">
            <div class="field">
                <label for="first_name">First name</label>
                <input id="first_name" name="first_name" value="{{ old('first_name', $profile['first_name'] ?? '') }}">
            </div>
            <div class="field">
                <label for="last_name">Last name</label>
                <input id="last_name" name="last_name" value="{{ old('last_name', $profile['last_name'] ?? '') }}">
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $profile['email'] ?? '') }}">
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="{{ old('phone', $profile['phone'] ?? '') }}">
            </div>
            <div class="field">
                <label for="password">New password</label>
                <input id="password" name="password" type="password" placeholder="Leave blank to keep the current password">
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Save profile</button>
    </form>
@endsection
