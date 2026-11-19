@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>{{ $definition['singular'] }}</h1>
            <p>GET /api/v1/{{ $definition['key'] }}/{{ $record['id'] ?? '' }}</p>
        </div>
        <div class="actions">
            <a class="btn" href="{{ route('admin.resources.index', $definition['key']) }}">Back</a>
            <a class="btn" href="{{ route('admin.resources.edit', [$definition['key'], $record['id']]) }}">Edit</a>
            <form method="POST" action="{{ route('admin.resources.destroy', [$definition['key'], $record['id']]) }}" onsubmit="return confirm('Delete this record?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>

    <article class="card">
        <dl class="dl">
            @foreach ($record as $key => $value)
                <dt>{{ str_replace('_', ' ', $key) }}</dt>
                <dd>
                    @if (is_array($value))
                        <pre>{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                    @elseif (is_bool($value))
                        {{ $value ? 'Yes' : 'No' }}
                    @else
                        {{ $value === null || $value === '' ? '—' : $value }}
                    @endif
                </dd>
            @endforeach
        </dl>
    </article>

    @if ($definition['key'] === 'orders' && ($record['status'] ?? '') !== 'cancelled')
        <form class="card" method="POST" action="{{ route('admin.resources.cancel', ['orders', $record['id']]) }}" style="margin-top: 12px">
            @csrf
            <h2>Cancel order</h2>
            <p class="hint">POST /api/v1/orders/{{ $record['id'] }}/cancel</p>
            <div class="field">
                <label for="reason">Reason</label>
                <input id="reason" name="reason" type="text" maxlength="500">
            </div>
            <button class="btn btn-danger" type="submit">Cancel order</button>
        </form>
    @endif
@endsection
