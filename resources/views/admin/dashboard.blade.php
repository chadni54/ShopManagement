@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>Dashboard</h1>
            <p>Counts and recent records from the ShopDemo API.</p>
        </div>
        <span class="badge {{ $healthOk ? 'badge-active' : 'badge-failed' }}">
            API {{ $health['status'] ?? 'unknown' }}
        </span>
    </div>

    <section class="stats">
        <article class="card stat"><strong>{{ $orderCount }}</strong><span>Orders</span></article>
        <article class="card stat"><strong>{{ $customerCount }}</strong><span>Customers</span></article>
        <article class="card stat"><strong>{{ $productCount }}</strong><span>Products</span></article>
        <article class="card stat"><strong>{{ $paymentCount }}</strong><span>Payments</span></article>
    </section>

    <section class="split">
        <article class="card">
            <div class="top">
                <h2>Recent orders</h2>
                <a href="{{ route('admin.resources.index', 'orders') }}">View all</a>
            </div>
            @if ($orders === [])
                <p class="empty">No orders yet.</p>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Number</th><th>Status</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.resources.show', ['orders', $order['id']]) }}">{{ $order['order_number'] ?? $order['id'] }}</a></td>
                                    <td><span class="badge badge-{{ $order['status'] ?? '' }}">{{ $order['status'] ?? '' }}</span></td>
                                    <td>{{ $order['total'] ?? '' }} {{ $order['currency'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
        <article class="card">
            <div class="top">
                <h2>Notifications</h2>
                <a href="{{ route('admin.notifications') }}">{{ $notificationCount }}</a>
            </div>
            @if ($notifications === [])
                <p class="empty">No notifications.</p>
            @else
                @foreach ($notifications as $notification)
                    <p>
                        <strong>{{ $notification['title'] ?? 'Notification' }}</strong><br>
                        <span class="hint">{{ $notification['body'] ?? '' }}</span>
                    </p>
                @endforeach
            @endif
        </article>
    </section>

    <section class="card" style="margin-top: 12px;">
        <div class="top">
            <h2>Support SMS</h2>
            <a href="{{ route('admin.support-sms') }}">{{ $smsCount }} · View all</a>
        </div>
        @if ($smsMessages->isEmpty())
            <p class="empty">No support SMS yet.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>From</th><th>Message</th><th>Ticket</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($smsMessages as $message)
                            <tr>
                                <td>{{ $message->direction === 'reply' ? 'Agent' : $message->from_number }}</td>
                                <td class="wrap">{{ $message->body }}</td>
                                <td>{{ $message->ams_ticket_number }}</td>
                                <td><span class="badge badge-{{ $message->status }}">{{ $message->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
