<?php

$out = dirname(__DIR__).'/app/OpenApi/EndpointDocs.php';

$schemas = [
    'UpdateProfile' => [
        'required' => [],
        'props' => [
            p('first_name', 'string', max: 100),
            p('last_name', 'string', max: 100),
            p('email', 'string', format: 'email'),
            p('phone', 'string', max: 30, nullable: true),
            p('password', 'string', format: 'password', min: 8, nullable: true),
        ],
    ],
    'StoreUser' => [
        'required' => ['first_name', 'last_name', 'email', 'password', 'role'],
        'props' => [
            p('first_name', 'string', max: 100),
            p('last_name', 'string', max: 100),
            p('email', 'string', format: 'email'),
            p('phone', 'string', max: 30, nullable: true),
            p('password', 'string', format: 'password', min: 8),
            p('role', 'string', enum: ['admin', 'staff', 'customer']),
            p('status', 'string', enum: ['active', 'inactive', 'suspended']),
        ],
    ],
    'UpdateUser' => [
        'required' => [],
        'props' => [
            p('first_name', 'string', max: 100),
            p('last_name', 'string', max: 100),
            p('email', 'string', format: 'email'),
            p('phone', 'string', max: 30, nullable: true),
            p('password', 'string', format: 'password', min: 8, nullable: true),
            p('role', 'string', enum: ['admin', 'staff', 'customer']),
            p('status', 'string', enum: ['active', 'inactive', 'suspended']),
        ],
    ],
    'StoreCustomer' => [
        'required' => ['user_id'],
        'props' => customerProps(true),
    ],
    'UpdateCustomer' => [
        'required' => [],
        'props' => customerProps(false),
    ],
    'StoreCategory' => [
        'required' => ['name', 'slug'],
        'props' => categoryProps(),
    ],
    'UpdateCategory' => [
        'required' => [],
        'props' => categoryProps(),
    ],
    'StoreBrand' => [
        'required' => ['name', 'slug'],
        'props' => brandProps(),
    ],
    'UpdateBrand' => [
        'required' => [],
        'props' => brandProps(),
    ],
    'StoreProduct' => [
        'required' => ['sku', 'name', 'slug', 'price'],
        'props' => productProps(),
    ],
    'UpdateProduct' => [
        'required' => [],
        'props' => productProps(),
    ],
    'StoreInventory' => [
        'required' => ['product_id', 'quantity'],
        'props' => inventoryProps(),
    ],
    'UpdateInventory' => [
        'required' => [],
        'props' => inventoryProps(),
    ],
    'StoreCoupon' => [
        'required' => ['code', 'type', 'value'],
        'props' => couponProps(),
    ],
    'UpdateCoupon' => [
        'required' => [],
        'props' => couponProps(),
    ],
    'StoreOrder' => [
        'required' => ['customer_id', 'items'],
        'props' => array_merge(orderProps(true), [
            "new OA\\Property(property: 'items', type: 'array', items: new OA\\Items(required: ['product_id', 'quantity'], properties: [new OA\\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'), new OA\\Property(property: 'quantity', type: 'integer', minimum: 1), new OA\\Property(property: 'unit_price', type: 'number', nullable: true), new OA\\Property(property: 'sku', type: 'string', nullable: true), new OA\\Property(property: 'name', type: 'string', nullable: true)]))",
        ]),
    ],
    'UpdateOrder' => [
        'required' => [],
        'props' => [
            p('status', 'string', enum: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
            p('discount', 'number', min: 0),
            p('tax', 'number', min: 0),
            p('shipping_amount', 'number', min: 0),
            p('notes', 'string', nullable: true),
            p('coupon_id', 'string', description: 'Coupon UUID or numeric id', nullable: true),
        ],
    ],
    'CancelOrder' => [
        'required' => [],
        'props' => [
            p('reason', 'string', max: 500, nullable: true),
        ],
    ],
    'StorePayment' => [
        'required' => ['order_id', 'method', 'amount'],
        'props' => paymentProps(),
    ],
    'UpdatePayment' => [
        'required' => [],
        'props' => paymentProps(),
    ],
    'StoreShipment' => [
        'required' => ['order_id'],
        'props' => shipmentProps(),
    ],
    'UpdateShipment' => [
        'required' => [],
        'props' => shipmentProps(),
    ],
    'StoreInvoice' => [
        'required' => ['order_id', 'subtotal', 'total'],
        'props' => invoiceProps(),
    ],
    'UpdateInvoice' => [
        'required' => [],
        'props' => invoiceProps(),
    ],
    'StoreReview' => [
        'required' => ['product_id', 'customer_id', 'rating'],
        'props' => reviewProps(),
    ],
    'UpdateReview' => [
        'required' => [],
        'props' => reviewProps(),
    ],
    'UpdateSetting' => [
        'required' => [],
        'props' => [
            p('enable_notifications', 'boolean'),
            p('enable_email', 'boolean'),
            p('enable_push', 'boolean'),
            p('language', 'string', max: 10),
            p('timezone', 'string', max: 64),
            p('currency', 'string', max: 3),
            "new OA\\Property(property: 'preferences', type: 'object', nullable: true)",
        ],
    ],
];

$resources = [
    ['Users', 'users', 'user', 'User', ['role', 'status'], ['created_at', 'name', 'email']],
    ['Customers', 'customers', 'customer', 'Customer', ['status', 'user_id', 'country'], ['created_at', 'customer_number', 'company_name']],
    ['Categories', 'categories', 'category', 'Category', ['is_active', 'parent_id'], ['created_at', 'name', 'sort_order']],
    ['Brands', 'brands', 'brand', 'Brand', ['is_active'], ['created_at', 'name']],
    ['Products', 'products', 'product', 'Product', ['category_id', 'brand_id', 'is_active', 'currency'], ['created_at', 'name', 'price', 'sku']],
    ['Inventory', 'inventory', 'inventory', 'Inventory', ['product_id', 'warehouse', 'status'], ['created_at', 'quantity', 'warehouse']],
    ['Coupons', 'coupons', 'coupon', 'Coupon', ['type', 'is_active'], ['created_at', 'code', 'starts_at', 'ends_at']],
    ['Orders', 'orders', 'order', 'Order', ['customer_id', 'status', 'currency', 'coupon_id'], ['created_at', 'placed_at', 'total', 'order_number']],
    ['Payments', 'payments', 'payment', 'Payment', ['order_id', 'status', 'method', 'currency'], ['created_at', 'amount', 'paid_at']],
    ['Shipping', 'shipping', 'shipping', 'Shipment', ['order_id', 'status', 'carrier'], ['created_at', 'shipped_at', 'delivered_at']],
    ['Invoices', 'invoices', 'invoice', 'Invoice', ['order_id', 'status'], ['created_at', 'issued_at', 'due_at', 'total']],
    ['Reviews', 'reviews', 'review', 'Review', ['product_id', 'customer_id', 'is_approved', 'rating'], ['created_at', 'rating', 'reviewed_at']],
];

$schemaAttrs = [];
foreach ($schemas as $name => $schema) {
    $required = $schema['required'] === []
        ? ''
        : 'required: '.exportList($schema['required']).', ';
    $props = implode(",\n            ", $schema['props']);
    $schemaAttrs[] = <<<ATTR
#[OA\Schema(
    schema: '$name',
    {$required}properties: [
            $props,
    ]
)]
ATTR;
}

$ops = [];
foreach ($resources as [$tag, $path, $param, $singular, $filters, $sorts]) {
    $base = '/api/v1/'.$path;
    $store = 'Store'.$singular;
    $update = 'Update'.$singular;
    $listParams = listParams($filters, $sorts);
    $idParam = "new OA\\Parameter(name: '$param', in: 'path', required: true, description: '$singular UUID', schema: new OA\\Schema(type: 'string', format: 'uuid'))";

    $ops[] = operation('Get', $base, $tag, "List {$path}", true, $listParams, null, [200 => 'List', 401 => 'Unauthenticated']);
    $ops[] = operation('Post', $base, $tag, "Create {$singular}", true, null, $store, [201 => 'Created', 401 => 'Unauthenticated', 422 => 'Validation failed']);
    $ops[] = operation('Get', $base.'/{'.$param.'}', $tag, "Show {$singular}", true, $idParam, null, [200 => 'Record', 401 => 'Unauthenticated', 404 => 'Not found']);
    $ops[] = operation('Put', $base.'/{'.$param.'}', $tag, "Update {$singular}", true, $idParam, $update, [200 => 'Updated', 401 => 'Unauthenticated', 404 => 'Not found', 422 => 'Validation failed']);
    $ops[] = operation('Delete', $base.'/{'.$param.'}', $tag, "Delete {$singular}", true, $idParam, null, [200 => 'Deleted', 401 => 'Unauthenticated', 404 => 'Not found']);
}

$ops[] = operation(
    'Post',
    '/api/v1/orders/{order}/cancel',
    'Orders',
    'Cancel order',
    true,
    "new OA\\Parameter(name: 'order', in: 'path', required: true, description: 'Order UUID', schema: new OA\\Schema(type: 'string', format: 'uuid'))",
    'CancelOrder',
    [200 => 'Cancelled', 401 => 'Unauthenticated', 404 => 'Not found', 422 => 'Validation failed']
);
$ops[] = operation('Get', '/api/v1/settings', 'Settings', 'Get settings for the authenticated user', true, null, null, [200 => 'Settings', 401 => 'Unauthenticated']);
$ops[] = operation('Put', '/api/v1/settings', 'Settings', 'Update settings', true, null, 'UpdateSetting', [200 => 'Updated', 401 => 'Unauthenticated', 422 => 'Validation failed']);
$ops[] = operation(
    'Get',
    '/api/v1/notifications',
    'Notifications',
    'List notifications for the authenticated user',
    true,
    listParams([], []),
    null,
    [200 => 'List', 401 => 'Unauthenticated']
);
$ops[] = operation('Post', '/api/v1/notifications/read-all', 'Notifications', 'Mark all notifications as read', true, null, null, [200 => 'Updated', 401 => 'Unauthenticated']);
$ops[] = operation(
    'Post',
    '/api/v1/notifications/{notification}/read',
    'Notifications',
    'Mark one notification as read',
    true,
    "new OA\\Parameter(name: 'notification', in: 'path', required: true, description: 'Notification UUID', schema: new OA\\Schema(type: 'string', format: 'uuid'))",
    null,
    [200 => 'Updated', 401 => 'Unauthenticated', 404 => 'Not found']
);

$body = <<<'PHP'
<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

PHP;

$body .= implode("\n", $schemaAttrs)."\n";
$body .= implode("\n", $ops)."\n";
$body .= "class EndpointDocs\n{\n}\n";

file_put_contents($out, $body);
echo "wrote {$out}\n";

function p(string $name, string $type, ?int $max = null, ?string $format = null, ?array $enum = null, ?int $min = null, bool $nullable = false, ?string $description = null): string
{
    $parts = ["property: '$name'", "type: '$type'"];
    if ($format) {
        $parts[] = "format: '$format'";
    }
    if ($enum) {
        $parts[] = 'enum: '.exportList($enum);
    }
    if ($max !== null) {
        $parts[] = "maxLength: $max";
    }
    if ($min !== null && $type === 'string') {
        $parts[] = "minLength: $min";
    }
    if ($min !== null && $type !== 'string') {
        $parts[] = "minimum: $min";
    }
    if ($nullable) {
        $parts[] = 'nullable: true';
    }
    if ($description) {
        $parts[] = 'description: '.var_export($description, true);
    }

    return 'new OA\\Property('.implode(', ', $parts).')';
}

function customerProps(bool $store): array
{
    return [
        p('user_id', 'string', description: 'User UUID or numeric id'),
        p('customer_number', 'string', max: 50, nullable: true),
        p('company_name', 'string', max: 255, nullable: true),
        p('billing_address', 'string', nullable: true),
        p('shipping_address', 'string', nullable: true),
        p('city', 'string', max: 100, nullable: true),
        p('state', 'string', max: 100, nullable: true),
        p('country', 'string', max: 100, nullable: true),
        p('postal_code', 'string', max: 20, nullable: true),
        p('status', 'string', enum: ['active', 'inactive']),
    ];
}

function categoryProps(): array
{
    return [
        p('name', 'string', max: 255),
        p('slug', 'string', max: 255),
        p('parent_id', 'string', description: 'Parent category UUID or numeric id', nullable: true),
        p('description', 'string', nullable: true),
        p('is_active', 'boolean'),
        p('sort_order', 'integer', min: 0),
    ];
}

function brandProps(): array
{
    return [
        p('name', 'string', max: 255),
        p('slug', 'string', max: 255),
        p('description', 'string', nullable: true),
        p('website', 'string', format: 'uri', max: 255, nullable: true),
        p('is_active', 'boolean'),
    ];
}

function productProps(): array
{
    return [
        p('category_id', 'string', description: 'Category UUID or numeric id', nullable: true),
        p('brand_id', 'string', description: 'Brand UUID or numeric id', nullable: true),
        p('sku', 'string', max: 100),
        p('name', 'string', max: 255),
        p('slug', 'string', max: 255),
        p('description', 'string', nullable: true),
        p('price', 'number', min: 0),
        p('compare_at_price', 'number', min: 0, nullable: true),
        p('cost', 'number', min: 0, nullable: true),
        p('currency', 'string', max: 3),
        p('is_active', 'boolean'),
        "new OA\\Property(property: 'attributes', type: 'object', nullable: true)",
    ];
}

function inventoryProps(): array
{
    return [
        p('product_id', 'string', description: 'Product UUID or numeric id'),
        p('quantity', 'integer', min: 0),
        p('reserved_quantity', 'integer', min: 0),
        p('warehouse', 'string', max: 100),
        p('low_stock_threshold', 'integer', min: 0),
        p('status', 'string', enum: ['in_stock', 'low_stock', 'out_of_stock']),
    ];
}

function couponProps(): array
{
    return [
        p('code', 'string', max: 50),
        p('type', 'string', enum: ['percent', 'fixed']),
        p('value', 'number', min: 0),
        p('min_order_amount', 'number', min: 0, nullable: true),
        p('max_uses', 'integer', min: 1, nullable: true),
        p('used_count', 'integer', min: 0),
        p('starts_at', 'string', format: 'date-time', nullable: true),
        p('ends_at', 'string', format: 'date-time', nullable: true),
        p('is_active', 'boolean'),
    ];
}

function orderProps(bool $store): array
{
    return [
        p('customer_id', 'string', description: 'Customer UUID or numeric id'),
        p('order_number', 'string', max: 50, nullable: true),
        p('status', 'string', enum: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
        p('discount', 'number', min: 0),
        p('tax', 'number', min: 0),
        p('shipping_amount', 'number', min: 0),
        p('currency', 'string', max: 3),
        p('coupon_id', 'string', description: 'Coupon UUID or numeric id', nullable: true),
        p('notes', 'string', nullable: true),
        p('placed_at', 'string', format: 'date-time', nullable: true),
    ];
}

function paymentProps(): array
{
    return [
        p('order_id', 'string', description: 'Order UUID or numeric id'),
        p('payment_number', 'string', max: 50, nullable: true),
        p('method', 'string', max: 50),
        p('amount', 'number', min: 0),
        p('currency', 'string', max: 3),
        p('status', 'string', enum: ['pending', 'completed', 'failed', 'refunded']),
        p('provider_reference', 'string', max: 255, nullable: true),
        p('paid_at', 'string', format: 'date-time', nullable: true),
    ];
}

function shipmentProps(): array
{
    return [
        p('order_id', 'string', description: 'Order UUID or numeric id'),
        p('carrier', 'string', max: 100, nullable: true),
        p('tracking_number', 'string', max: 100, nullable: true),
        p('status', 'string', max: 50),
        p('shipped_at', 'string', format: 'date-time', nullable: true),
        p('delivered_at', 'string', format: 'date-time', nullable: true),
        "new OA\\Property(property: 'address', type: 'object', nullable: true)",
    ];
}

function invoiceProps(): array
{
    return [
        p('order_id', 'string', description: 'Order UUID or numeric id'),
        p('invoice_number', 'string', max: 50, nullable: true),
        p('status', 'string', max: 50),
        p('subtotal', 'number', min: 0),
        p('tax', 'number', min: 0),
        p('total', 'number', min: 0),
        p('issued_at', 'string', format: 'date-time', nullable: true),
        p('due_at', 'string', format: 'date-time', nullable: true),
        p('pdf_path', 'string', max: 255, nullable: true),
    ];
}

function reviewProps(): array
{
    return [
        p('product_id', 'string', description: 'Product UUID or numeric id'),
        p('customer_id', 'string', description: 'Customer UUID or numeric id'),
        p('rating', 'integer', min: 1),
        p('title', 'string', max: 255, nullable: true),
        p('body', 'string', nullable: true),
        p('is_approved', 'boolean'),
        p('reviewed_at', 'string', format: 'date-time', nullable: true),
    ];
}

function exportList(array $values): string
{
    $quoted = array_map(fn ($value) => var_export($value, true), $values);

    return '['.implode(', ', $quoted).']';
}

function listParams(array $filters, array $sorts): string
{
    $params = [
        "new OA\\Parameter(name: 'page', in: 'query', required: false, schema: new OA\\Schema(type: 'integer', minimum: 1))",
        "new OA\\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\\Schema(type: 'integer', minimum: 1, maximum: 100))",
    ];

    if ($filters !== [] || $sorts !== []) {
        array_unshift($params, "new OA\\Parameter(name: 'search', in: 'query', required: false, schema: new OA\\Schema(type: 'string'))");
    }

    if ($sorts !== []) {
        $params[] = "new OA\\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\\Schema(type: 'string', enum: ".exportList($sorts).'))';
        $params[] = "new OA\\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\\Schema(type: 'string', enum: ['asc', 'desc']))";
    }

    foreach ($filters as $filter) {
        $params[] = "new OA\\Parameter(name: '$filter', in: 'query', required: false, schema: new OA\\Schema(type: 'string'))";
    }

    return implode(",\n            ", $params);
}

function operation(string $method, string $path, string $tag, string $summary, bool $auth, ?string $parameters, ?string $schema, array $responses): string
{
    $security = $auth ? "security: [['bearerAuth' => []]],\n        " : '';
    $params = $parameters ? "parameters: [\n            $parameters,\n        ],\n        " : '';
    $body = $schema ? "requestBody: new OA\\RequestBody(required: true, content: new OA\\JsonContent(ref: '#/components/schemas/$schema')),\n        " : '';
    $responseLines = [];
    foreach ($responses as $code => $description) {
        $responseLines[] = "new OA\\Response(response: $code, description: '$description')";
    }
    $responseBlock = implode(",\n            ", $responseLines);

    return <<<ATTR
#[OA\\$method(
    path: '$path',
    tags: ['$tag'],
    summary: '$summary',
    {$security}{$params}{$body}responses: [
            $responseBlock,
    ]
)]
ATTR;
}
