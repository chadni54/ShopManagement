<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ApiResult;
use App\Admin\ResourceCatalog;
use App\Admin\ShopApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use JsonException;
use RuntimeException;

class ResourcePageController extends Controller
{
    public function __construct(private ShopApi $api) {}

    public function index(Request $request, string $resource): View|RedirectResponse
    {
        $definition = $this->definition($resource);
        $query = [
            'page' => max(1, (int) $request->input('page', 1)),
            'per_page' => 15,
        ];

        if ($request->filled('search')) {
            $query['search'] = $request->string('search')->toString();
        }

        $result = $this->api->get($resource, $query);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return view('admin.resources.index', [
            'definition' => $definition,
            'rows' => $result->rows(),
            'meta' => $result->meta,
            'search' => $request->string('search')->toString(),
            'error' => $result->successful ? null : ($result->message ?: 'Could not load records.'),
        ]);
    }

    public function create(string $resource): View|RedirectResponse
    {
        $definition = $this->definition($resource);
        $fields = ResourceCatalog::fieldsFor($definition['fields'], 'create');
        $lookups = $this->lookups($fields);

        if ($lookups instanceof RedirectResponse) {
            return $lookups;
        }

        $productOptions = $resource === 'orders' ? $this->options('products', ['sku', 'name']) : [];

        if ($productOptions instanceof RedirectResponse) {
            return $productOptions;
        }

        return view('admin.resources.form', [
            'definition' => $definition,
            'mode' => 'create',
            'record' => [],
            'fields' => $fields,
            'lookups' => $lookups,
            'productOptions' => $productOptions,
        ]);
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $definition = $this->definition($resource);
        $fields = ResourceCatalog::fieldsFor($definition['fields'], 'create');

        try {
            $payload = $this->payload($request, $fields, null);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        if ($resource === 'orders') {
            $payload['items'] = $this->orderItems($request);
        }

        $result = $this->api->post($resource, $payload);

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        $id = $result->record()['id'] ?? null;

        if ($id) {
            return redirect()
                ->route('admin.resources.show', [$resource, $id])
                ->with('success', $result->message ?: 'Created successfully');
        }

        return redirect()
            ->route('admin.resources.index', $resource)
            ->with('success', $result->message ?: 'Created successfully');
    }

    public function show(string $resource, string $id): View|RedirectResponse
    {
        $definition = $this->definition($resource);
        $result = $this->api->get($resource.'/'.$id);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        if (! $result->successful) {
            return redirect()
                ->route('admin.resources.index', $resource)
                ->with('error', $result->message ?: 'Record not found.');
        }

        return view('admin.resources.show', [
            'definition' => $definition,
            'record' => $result->record(),
        ]);
    }

    public function edit(string $resource, string $id): View|RedirectResponse
    {
        $definition = $this->definition($resource);
        $result = $this->api->get($resource.'/'.$id);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        if (! $result->successful) {
            return redirect()
                ->route('admin.resources.index', $resource)
                ->with('error', $result->message ?: 'Record not found.');
        }

        $fields = ResourceCatalog::fieldsFor($definition['fields'], 'edit');
        $lookups = $this->lookups($fields);

        if ($lookups instanceof RedirectResponse) {
            return $lookups;
        }

        return view('admin.resources.form', [
            'definition' => $definition,
            'mode' => 'edit',
            'record' => $result->record(),
            'fields' => $fields,
            'lookups' => $lookups,
            'productOptions' => [],
        ]);
    }

    public function update(Request $request, string $resource, string $id): RedirectResponse
    {
        $definition = $this->definition($resource);
        $current = $this->api->get($resource.'/'.$id);

        if ($redirect = $this->expired($current)) {
            return $redirect;
        }

        $fields = ResourceCatalog::fieldsFor($definition['fields'], 'edit');

        try {
            $payload = $this->payload($request, $fields, $current->record());
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        $result = $this->api->put($resource.'/'.$id, $payload);

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        return redirect()
            ->route('admin.resources.show', [$resource, $id])
            ->with('success', $result->message ?: 'Updated successfully');
    }

    public function destroy(string $resource, string $id): RedirectResponse
    {
        $this->definition($resource);
        $result = $this->api->delete($resource.'/'.$id);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        if (! $result->successful) {
            return back()->with('error', $result->message ?: 'Could not delete this record.');
        }

        return redirect()
            ->route('admin.resources.index', $resource)
            ->with('success', $result->message ?: 'Deleted successfully');
    }

    public function cancel(Request $request, string $resource, string $id): RedirectResponse
    {
        abort_unless($resource === 'orders', 404);

        $result = $this->api->post($resource.'/'.$id.'/cancel', [
            'reason' => $request->string('reason')->toString(),
        ]);

        if (! $result->successful) {
            return $this->backWithError($result);
        }

        return redirect()
            ->route('admin.resources.show', [$resource, $id])
            ->with('success', $result->message ?: 'Order cancelled');
    }

    /**
     * @return array<string, mixed>
     */
    private function definition(string $resource): array
    {
        $definition = ResourceCatalog::find($resource);
        abort_unless($definition !== null, 404);

        return $definition;
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

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return array<string, list<array{value: string, label: string}>>|RedirectResponse
     */
    private function lookups(array $fields): array|RedirectResponse
    {
        $lookups = [];

        foreach ($fields as $field) {
            if (($field['type'] ?? '') !== 'lookup') {
                continue;
            }

            $options = $this->options($field['resource'], $field['label_keys']);

            if ($options instanceof RedirectResponse) {
                return $options;
            }

            $lookups[$field['name']] = $options;
        }

        return $lookups;
    }

    /**
     * @param  list<string>  $labelKeys
     * @return list<array{value: string, label: string}>|RedirectResponse
     */
    private function options(string $resource, array $labelKeys): array|RedirectResponse
    {
        $result = $this->api->get($resource, ['per_page' => 100]);

        if ($redirect = $this->expired($result)) {
            return $redirect;
        }

        return array_map(function (array $row) use ($labelKeys): array {
            $parts = [];

            foreach ($labelKeys as $key) {
                if (isset($row[$key]) && $row[$key] !== '' && $row[$key] !== null) {
                    $parts[] = is_scalar($row[$key]) ? (string) $row[$key] : '';
                }
            }

            return [
                'value' => (string) ($row['id'] ?? ''),
                'label' => $parts !== [] ? implode(' · ', array_filter($parts)) : (string) ($row['id'] ?? ''),
            ];
        }, $result->rows());
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @param  array<string, mixed>|null  $record
     * @return array<string, mixed>
     */
    private function payload(Request $request, array $fields, ?array $record): array
    {
        $creating = $record === null;
        $data = [];

        foreach ($fields as $field) {
            $name = $field['name'];

            if (! $request->exists($name)) {
                continue;
            }

            if ($field['type'] === 'password' && ! $request->filled($name)) {
                continue;
            }

            try {
                $value = $this->normalize($field, $request->input($name));
            } catch (JsonException) {
                throw new RuntimeException($field['label'].' must be valid JSON.');
            }

            if (! $creating && $this->unchanged($value, $record[$name] ?? null)) {
                continue;
            }

            if ($creating && ($value === null || $value === '') && empty($field['required'])) {
                continue;
            }

            $data[$name] = $value;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $field
     */
    private function normalize(array $field, mixed $value): mixed
    {
        if ($field['type'] === 'json') {
            if ($value === null || $value === '') {
                return null;
            }

            $decoded = json_decode((string) $value, true, 512, JSON_THROW_ON_ERROR);

            return $decoded;
        }

        if ($value === '') {
            return null;
        }

        return $value;
    }

    private function unchanged(mixed $submitted, mixed $existing): bool
    {
        if (is_bool($existing)) {
            return ($existing ? '1' : '0') === (string) $submitted
                || ($existing ? 'true' : 'false') === (string) $submitted;
        }

        if (is_array($existing) || is_array($submitted)) {
            return json_encode($existing) === json_encode($submitted);
        }

        if ($existing === null || $existing === '') {
            return $submitted === null || $submitted === '';
        }

        return (string) $existing === (string) $submitted;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function orderItems(Request $request): array
    {
        $items = [];

        foreach ($request->input('items', []) as $item) {
            if (! is_array($item) || empty($item['product_id'])) {
                continue;
            }

            $row = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'] ?? 1,
            ];

            if (($item['unit_price'] ?? '') !== '') {
                $row['unit_price'] = $item['unit_price'];
            }

            $items[] = $row;
        }

        return $items;
    }
}
