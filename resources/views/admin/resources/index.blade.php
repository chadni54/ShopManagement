@extends('admin.layout')

@section('content')
    <div class="top">
        <div>
            <h1>{{ $definition['label'] }}</h1>
            <p>GET /api/v1/{{ $definition['key'] }}</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.resources.create', $definition['key']) }}">New {{ strtolower($definition['singular']) }}</a>
    </div>

    @if ($error)
        <div class="flash flash-bad">{{ $error }}</div>
    @endif

    <form class="search" method="GET" action="{{ route('admin.resources.index', $definition['key']) }}">
        <input name="search" value="{{ $search }}" placeholder="Search {{ strtolower($definition['label']) }}" aria-label="Search">
        <button class="btn" type="submit">Search</button>
    </form>

    <div class="card table-wrap">
        @if ($rows === [])
            <p class="empty">No records found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        @foreach ($definition['columns'] as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ($definition['columns'] as $column)
                                @php
                                    $value = $row[$column['key']] ?? '';
                                    if (is_bool($value)) {
                                        $value = $value ? 'Yes' : 'No';
                                    } elseif (is_array($value)) {
                                        $value = json_encode($value);
                                    }
                                @endphp
                                <td>
                                    @if (! empty($column['badge']))
                                        <span class="badge badge-{{ \Illuminate\Support\Str::lower((string) ($row[$column['key']] ?? '')) }}">{{ $value }}</span>
                                    @else
                                        {{ \Illuminate\Support\Str::limit((string) $value, 80) }}
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.resources.show', [$definition['key'], $row['id']]) }}">View</a>
                                    <a href="{{ route('admin.resources.edit', [$definition['key'], $row['id']]) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.resources.destroy', [$definition['key'], $row['id']]) }}" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @php
        $current = (int) ($meta['current_page'] ?? 1);
        $last = (int) ($meta['last_page'] ?? 1);
        $total = (int) ($meta['total'] ?? count($rows));
    @endphp
    <div class="pager">
        <span>{{ $total }} total · page {{ $current }} of {{ max($last, 1) }}</span>
        <div class="actions">
            @if ($current > 1)
                <a class="btn" href="{{ route('admin.resources.index', ['resource' => $definition['key'], 'page' => $current - 1, 'search' => $search]) }}">Previous</a>
            @endif
            @if ($current < $last)
                <a class="btn" href="{{ route('admin.resources.index', ['resource' => $definition['key'], 'page' => $current + 1, 'search' => $search]) }}">Next</a>
            @endif
        </div>
    </div>
@endsection
