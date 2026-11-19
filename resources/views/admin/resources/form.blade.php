@extends('admin.layout')

@section('content')
    @php
        $isCreate = $mode === 'create';
        $action = $isCreate
            ? route('admin.resources.store', $definition['key'])
            : route('admin.resources.update', [$definition['key'], $record['id']]);
    @endphp

    <div class="top">
        <div>
            <h1>{{ $isCreate ? 'New' : 'Edit' }} {{ strtolower($definition['singular']) }}</h1>
            <p>{{ $isCreate ? 'POST' : 'PUT' }} /api/v1/{{ $definition['key'] }}{{ $isCreate ? '' : '/'.$record['id'] }}</p>
        </div>
        <a class="btn" href="{{ route('admin.resources.index', $definition['key']) }}">Back</a>
    </div>

    <form class="card" method="POST" action="{{ $action }}">
        @csrf
        @unless ($isCreate)
            @method('PUT')
        @endunless

        <div class="grid-2">
            @foreach ($fields as $field)
                @php
                    $current = old($field['name'], $record[$field['name']] ?? '');
                    if (is_array($current)) {
                        $current = json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    }
                    if ($field['type'] === 'datetime' && is_string($current) && $current !== '') {
                        try {
                            $current = \Illuminate\Support\Carbon::parse($current)->format('Y-m-d\TH:i');
                        } catch (\Throwable) {
                        }
                    }
                    if (is_bool($current)) {
                        $current = $current ? '1' : '0';
                    }
                    $wide = in_array($field['type'], ['textarea', 'json'], true);
                @endphp
                <div class="field" @if ($wide) style="grid-column: 1 / -1" @endif>
                    <label for="field-{{ $field['name'] }}">{{ $field['label'] }}</label>
                    @if ($field['type'] === 'textarea' || $field['type'] === 'json')
                        <textarea id="field-{{ $field['name'] }}" name="{{ $field['name'] }}">{{ $current }}</textarea>
                    @elseif ($field['type'] === 'select' || $field['type'] === 'bool')
                        <select id="field-{{ $field['name'] }}" name="{{ $field['name'] }}">
                            @unless (! empty($field['required']))
                                <option value="">—</option>
                            @endunless
                            @foreach (($field['type'] === 'bool' ? ['1' => 'Yes', '0' => 'No'] : $field['options']) as $value => $label)
                                <option value="{{ $value }}" @selected((string) $current === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @elseif ($field['type'] === 'lookup')
                        @php
                            $options = $lookups[$field['name']] ?? [];
                            $known = array_map(fn ($option) => (string) $option['value'], $options);
                            if ((string) $current !== '' && ! in_array((string) $current, $known, true)) {
                                array_unshift($options, ['value' => (string) $current, 'label' => 'Current ('.$current.')']);
                            }
                        @endphp
                        <select id="field-{{ $field['name'] }}" name="{{ $field['name'] }}" @if (! empty($field['required']) && $isCreate) required @endif>
                            @unless (! empty($field['required']))
                                <option value="">—</option>
                            @endunless
                            @foreach ($options as $option)
                                <option value="{{ $option['value'] }}" @selected((string) $current === (string) $option['value'])>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    @else
                        <input
                            id="field-{{ $field['name'] }}"
                            name="{{ $field['name'] }}"
                            type="{{ $field['type'] === 'password' ? 'password' : ($field['type'] === 'number' ? 'number' : ($field['type'] === 'email' ? 'email' : ($field['type'] === 'datetime' ? 'datetime-local' : 'text'))) }}"
                            value="{{ $field['type'] === 'password' ? '' : $current }}"
                            @if (! empty($field['required']) && $field['type'] !== 'password') required @endif
                            @if ($field['type'] === 'password' && ! empty($field['required'])) required @endif
                            @isset($field['min']) min="{{ $field['min'] }}" @endisset
                            @isset($field['max']) max="{{ $field['max'] }}" @endisset
                            @isset($field['step']) step="{{ $field['step'] }}" @endisset
                            @if ($field['type'] === 'password' && empty($field['required'])) placeholder="Leave blank to keep the current password" @endif
                        >
                    @endif
                </div>
            @endforeach
        </div>

        @if ($definition['key'] === 'orders' && $isCreate)
            <h2>Items</h2>
            <p class="hint">POST /api/v1/orders requires at least one product.</p>
            <div id="order-items">
                <div class="item-row">
                    <select name="items[0][product_id]" required>
                        <option value="">Product</option>
                        @foreach ($productOptions as $option)
                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                    <input name="items[0][quantity]" type="number" min="1" step="1" value="1" aria-label="Quantity" required>
                    <input name="items[0][unit_price]" type="number" min="0" step="0.01" placeholder="Unit price" aria-label="Unit price">
                    <button class="btn" type="button" onclick="addOrderItem()">Add</button>
                </div>
            </div>
        @endif

        <button class="btn btn-primary" type="submit">{{ $isCreate ? 'Create' : 'Save' }}</button>
    </form>

    @if ($definition['key'] === 'orders' && $isCreate)
        <script>
            function addOrderItem() {
                const wrap = document.getElementById('order-items');
                const rows = wrap.querySelectorAll('.item-row');
                const clone = rows[0].cloneNode(true);
                const index = rows.length;
                clone.querySelectorAll('[name]').forEach((el) => {
                    el.name = el.name.replace(/items\[\d+]/, 'items[' + index + ']');
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    }
                    if (el.tagName === 'INPUT' && el.name.includes('quantity')) {
                        el.value = '1';
                    }
                    if (el.tagName === 'INPUT' && el.name.includes('unit_price')) {
                        el.value = '';
                    }
                });
                const button = clone.querySelector('button');
                button.textContent = 'Remove';
                button.onclick = () => clone.remove();
                wrap.appendChild(clone);
            }
        </script>
    @endif
@endsection
