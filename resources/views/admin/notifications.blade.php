@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>Notifications</h1>
            <p>GET /api/v1/notifications</p>
        </div>
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button class="btn" type="submit">Mark all read</button>
        </form>
    </div>

    @if ($error)
        <div class="flash flash-bad">{{ $error }}</div>
    @endif

    <div class="card table-wrap">
        @if ($rows === [])
            <p class="empty">No notifications.</p>
        @else
            <table>
                <thead>
                    <tr><th>Title</th><th>Type</th><th>Read</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td class="wrap">
                                <strong>{{ $row['title'] ?? '' }}</strong><br>
                                {{ $row['body'] ?? '' }}
                            </td>
                            <td>{{ $row['type'] ?? '' }}</td>
                            <td>{{ empty($row['read_at']) ? 'Unread' : 'Read' }}</td>
                            <td>
                                @if (empty($row['read_at']))
                                    <form method="POST" action="{{ route('admin.notifications.read', $row['id']) }}">
                                        @csrf
                                        <button class="btn" type="submit">Mark read</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
