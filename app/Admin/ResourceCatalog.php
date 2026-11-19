<?php

namespace App\Admin;

class ResourceCatalog
{
    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::all());
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $key): ?array
    {
        $resources = self::all();

        if (! isset($resources[$key])) {
            return null;
        }

        return array_merge($resources[$key], ['key' => $key]);
    }

    /**
     * @return list<array{label: string, items: list<array{label: string, url: string, pattern: string, route?: string}>}>
     */
    public static function navigation(): array
    {
        $link = function (string $label, string $pattern, string $route, mixed $params = []): array {
            return [
                'label' => $label,
                'url' => route($route, $params),
                'pattern' => $pattern,
                'route' => $route,
            ];
        };

        return [
            [
                'label' => 'Overview',
                'items' => [
                    $link('Dashboard', 'dashboard', 'dashboard'),
                ],
            ],
            [
                'label' => 'Catalog',
                'items' => [
                    $link('Categories', 'dashboard/categories*', 'admin.resources.index', 'categories'),
                    $link('Brands', 'dashboard/brands*', 'admin.resources.index', 'brands'),
                    $link('Products', 'dashboard/products*', 'admin.resources.index', 'products'),
                    $link('Inventory', 'dashboard/inventory*', 'admin.resources.index', 'inventory'),
                    $link('Reviews', 'dashboard/reviews*', 'admin.resources.index', 'reviews'),
                ],
            ],
            [
                'label' => 'Sales',
                'items' => [
                    $link('Customers', 'dashboard/customers*', 'admin.resources.index', 'customers'),
                    $link('Orders', 'dashboard/orders*', 'admin.resources.index', 'orders'),
                    $link('Coupons', 'dashboard/coupons*', 'admin.resources.index', 'coupons'),
                    $link('Payments', 'dashboard/payments*', 'admin.resources.index', 'payments'),
                    $link('Shipping', 'dashboard/shipping*', 'admin.resources.index', 'shipping'),
                    $link('Invoices', 'dashboard/invoices*', 'admin.resources.index', 'invoices'),
                ],
            ],
            [
                'label' => 'Support',
                'items' => [
                    $link('SMS', 'dashboard/support-sms*', 'admin.support-sms'),
                ],
            ],
            [
                'label' => 'Account',
                'items' => [
                    $link('Users', 'dashboard/users*', 'admin.resources.index', 'users'),
                    $link('Notifications', 'dashboard/notifications*', 'admin.notifications'),
                    $link('Settings', 'dashboard/settings*', 'admin.settings'),
                    $link('Profile', 'dashboard/profile*', 'admin.profile'),
                ],
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @return list<array<string, mixed>>
     */
    public static function fieldsFor(array $fields, string $mode): array
    {
        return array_values(array_filter(
            $fields,
            fn (array $field): bool => ! isset($field['only']) || $field['only'] === $mode
        ));
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            'users' => [
                'label' => 'Users',
                'singular' => 'User',
                'columns' => [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'role', 'label' => 'Role', 'badge' => true],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'first_name', 'label' => 'First name', 'type' => 'text', 'required' => true],
                    ['name' => 'last_name', 'label' => 'Last name', 'type' => 'text', 'required' => true],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                    ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
                    ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true, 'only' => 'create'],
                    ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'only' => 'edit'],
                    ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'required' => true, 'options' => ['admin' => 'Admin', 'staff' => 'Staff', 'customer' => 'Customer']],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended']],
                ],
            ],
            'customers' => [
                'label' => 'Customers',
                'singular' => 'Customer',
                'columns' => [
                    ['key' => 'customer_number', 'label' => 'Number'],
                    ['key' => 'company_name', 'label' => 'Company'],
                    ['key' => 'city', 'label' => 'City'],
                    ['key' => 'country', 'label' => 'Country'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'user_id', 'label' => 'User', 'type' => 'lookup', 'resource' => 'users', 'label_keys' => ['name', 'email'], 'required' => true],
                    ['name' => 'customer_number', 'label' => 'Customer number', 'type' => 'text'],
                    ['name' => 'company_name', 'label' => 'Company', 'type' => 'text'],
                    ['name' => 'billing_address', 'label' => 'Billing address', 'type' => 'textarea'],
                    ['name' => 'shipping_address', 'label' => 'Shipping address', 'type' => 'textarea'],
                    ['name' => 'city', 'label' => 'City', 'type' => 'text'],
                    ['name' => 'state', 'label' => 'State', 'type' => 'text'],
                    ['name' => 'country', 'label' => 'Country', 'type' => 'text'],
                    ['name' => 'postal_code', 'label' => 'Postal code', 'type' => 'text'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive']],
                ],
            ],
            'categories' => [
                'label' => 'Categories',
                'singular' => 'Category',
                'columns' => [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'slug', 'label' => 'Slug'],
                    ['key' => 'sort_order', 'label' => 'Sort'],
                    ['key' => 'is_active', 'label' => 'Active', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'parent_id', 'label' => 'Parent category', 'type' => 'lookup', 'resource' => 'categories', 'label_keys' => ['name']],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'min' => 0, 'step' => '1'],
                    ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
                ],
            ],
            'brands' => [
                'label' => 'Brands',
                'singular' => 'Brand',
                'columns' => [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'slug', 'label' => 'Slug'],
                    ['key' => 'website', 'label' => 'Website'],
                    ['key' => 'is_active', 'label' => 'Active', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'website', 'label' => 'Website', 'type' => 'text'],
                    ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
                ],
            ],
            'products' => [
                'label' => 'Products',
                'singular' => 'Product',
                'columns' => [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'sku', 'label' => 'SKU'],
                    ['key' => 'price', 'label' => 'Price'],
                    ['key' => 'currency', 'label' => 'Currency'],
                    ['key' => 'is_active', 'label' => 'Active', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                    ['name' => 'sku', 'label' => 'SKU', 'type' => 'text', 'required' => true],
                    ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'required' => true],
                    ['name' => 'category_id', 'label' => 'Category', 'type' => 'lookup', 'resource' => 'categories', 'label_keys' => ['name']],
                    ['name' => 'brand_id', 'label' => 'Brand', 'type' => 'lookup', 'resource' => 'brands', 'label_keys' => ['name']],
                    ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '0.01'],
                    ['name' => 'compare_at_price', 'label' => 'Compare at price', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'cost', 'label' => 'Cost', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'currency', 'label' => 'Currency', 'type' => 'text'],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
                    ['name' => 'attributes', 'label' => 'Attributes (JSON)', 'type' => 'json'],
                ],
            ],
            'inventory' => [
                'label' => 'Inventory',
                'singular' => 'Inventory item',
                'columns' => [
                    ['key' => 'warehouse', 'label' => 'Warehouse'],
                    ['key' => 'quantity', 'label' => 'Quantity'],
                    ['key' => 'reserved_quantity', 'label' => 'Reserved'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'product_id', 'label' => 'Product', 'type' => 'lookup', 'resource' => 'products', 'label_keys' => ['sku', 'name'], 'required' => true],
                    ['name' => 'quantity', 'label' => 'Quantity', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '1'],
                    ['name' => 'reserved_quantity', 'label' => 'Reserved quantity', 'type' => 'number', 'min' => 0, 'step' => '1'],
                    ['name' => 'warehouse', 'label' => 'Warehouse', 'type' => 'text'],
                    ['name' => 'low_stock_threshold', 'label' => 'Low stock threshold', 'type' => 'number', 'min' => 0, 'step' => '1'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['in_stock' => 'In stock', 'low_stock' => 'Low stock', 'out_of_stock' => 'Out of stock']],
                ],
            ],
            'coupons' => [
                'label' => 'Coupons',
                'singular' => 'Coupon',
                'columns' => [
                    ['key' => 'code', 'label' => 'Code'],
                    ['key' => 'type', 'label' => 'Type', 'badge' => true],
                    ['key' => 'value', 'label' => 'Value'],
                    ['key' => 'used_count', 'label' => 'Used'],
                    ['key' => 'is_active', 'label' => 'Active', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'required' => true],
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'required' => true, 'options' => ['percent' => 'Percent', 'fixed' => 'Fixed']],
                    ['name' => 'value', 'label' => 'Value', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '0.01'],
                    ['name' => 'min_order_amount', 'label' => 'Minimum order amount', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'max_uses', 'label' => 'Max uses', 'type' => 'number', 'min' => 1, 'step' => '1'],
                    ['name' => 'used_count', 'label' => 'Used count', 'type' => 'number', 'min' => 0, 'step' => '1'],
                    ['name' => 'starts_at', 'label' => 'Starts', 'type' => 'datetime'],
                    ['name' => 'ends_at', 'label' => 'Ends', 'type' => 'datetime'],
                    ['name' => 'is_active', 'label' => 'Active', 'type' => 'bool'],
                ],
            ],
            'orders' => [
                'label' => 'Orders',
                'singular' => 'Order',
                'columns' => [
                    ['key' => 'order_number', 'label' => 'Number'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                    ['key' => 'total', 'label' => 'Total'],
                    ['key' => 'currency', 'label' => 'Currency'],
                    ['key' => 'placed_at', 'label' => 'Placed'],
                ],
                'fields' => [
                    ['name' => 'customer_id', 'label' => 'Customer', 'type' => 'lookup', 'resource' => 'customers', 'label_keys' => ['customer_number', 'company_name'], 'required' => true, 'only' => 'create'],
                    ['name' => 'order_number', 'label' => 'Order number', 'type' => 'text', 'only' => 'create'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'paid' => 'Paid', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled']],
                    ['name' => 'coupon_id', 'label' => 'Coupon', 'type' => 'lookup', 'resource' => 'coupons', 'label_keys' => ['code']],
                    ['name' => 'discount', 'label' => 'Discount', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'tax', 'label' => 'Tax', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'shipping_amount', 'label' => 'Shipping amount', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'currency', 'label' => 'Currency', 'type' => 'text', 'only' => 'create'],
                    ['name' => 'placed_at', 'label' => 'Placed at', 'type' => 'datetime', 'only' => 'create'],
                    ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea'],
                ],
            ],
            'payments' => [
                'label' => 'Payments',
                'singular' => 'Payment',
                'columns' => [
                    ['key' => 'payment_number', 'label' => 'Number'],
                    ['key' => 'method', 'label' => 'Method'],
                    ['key' => 'amount', 'label' => 'Amount'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                ],
                'fields' => [
                    ['name' => 'order_id', 'label' => 'Order', 'type' => 'lookup', 'resource' => 'orders', 'label_keys' => ['order_number', 'status'], 'required' => true],
                    ['name' => 'payment_number', 'label' => 'Payment number', 'type' => 'text'],
                    ['name' => 'method', 'label' => 'Method', 'type' => 'text', 'required' => true],
                    ['name' => 'amount', 'label' => 'Amount', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '0.01'],
                    ['name' => 'currency', 'label' => 'Currency', 'type' => 'text'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded']],
                    ['name' => 'provider_reference', 'label' => 'Provider reference', 'type' => 'text'],
                    ['name' => 'paid_at', 'label' => 'Paid at', 'type' => 'datetime'],
                ],
            ],
            'shipping' => [
                'label' => 'Shipping',
                'singular' => 'Shipment',
                'columns' => [
                    ['key' => 'carrier', 'label' => 'Carrier'],
                    ['key' => 'tracking_number', 'label' => 'Tracking'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                    ['key' => 'shipped_at', 'label' => 'Shipped'],
                ],
                'fields' => [
                    ['name' => 'order_id', 'label' => 'Order', 'type' => 'lookup', 'resource' => 'orders', 'label_keys' => ['order_number', 'status'], 'required' => true],
                    ['name' => 'carrier', 'label' => 'Carrier', 'type' => 'text'],
                    ['name' => 'tracking_number', 'label' => 'Tracking number', 'type' => 'text'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'text'],
                    ['name' => 'shipped_at', 'label' => 'Shipped at', 'type' => 'datetime'],
                    ['name' => 'delivered_at', 'label' => 'Delivered at', 'type' => 'datetime'],
                    ['name' => 'address', 'label' => 'Address (JSON)', 'type' => 'json'],
                ],
            ],
            'invoices' => [
                'label' => 'Invoices',
                'singular' => 'Invoice',
                'columns' => [
                    ['key' => 'invoice_number', 'label' => 'Number'],
                    ['key' => 'status', 'label' => 'Status', 'badge' => true],
                    ['key' => 'total', 'label' => 'Total'],
                    ['key' => 'due_at', 'label' => 'Due'],
                ],
                'fields' => [
                    ['name' => 'order_id', 'label' => 'Order', 'type' => 'lookup', 'resource' => 'orders', 'label_keys' => ['order_number', 'status'], 'required' => true],
                    ['name' => 'invoice_number', 'label' => 'Invoice number', 'type' => 'text'],
                    ['name' => 'status', 'label' => 'Status', 'type' => 'text'],
                    ['name' => 'subtotal', 'label' => 'Subtotal', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '0.01'],
                    ['name' => 'tax', 'label' => 'Tax', 'type' => 'number', 'min' => 0, 'step' => '0.01'],
                    ['name' => 'total', 'label' => 'Total', 'type' => 'number', 'required' => true, 'min' => 0, 'step' => '0.01'],
                    ['name' => 'issued_at', 'label' => 'Issued at', 'type' => 'datetime'],
                    ['name' => 'due_at', 'label' => 'Due at', 'type' => 'datetime'],
                    ['name' => 'pdf_path', 'label' => 'PDF path', 'type' => 'text'],
                ],
            ],
            'reviews' => [
                'label' => 'Reviews',
                'singular' => 'Review',
                'columns' => [
                    ['key' => 'title', 'label' => 'Title'],
                    ['key' => 'rating', 'label' => 'Rating'],
                    ['key' => 'is_approved', 'label' => 'Approved', 'badge' => true],
                    ['key' => 'reviewed_at', 'label' => 'Reviewed'],
                ],
                'fields' => [
                    ['name' => 'product_id', 'label' => 'Product', 'type' => 'lookup', 'resource' => 'products', 'label_keys' => ['sku', 'name'], 'required' => true],
                    ['name' => 'customer_id', 'label' => 'Customer', 'type' => 'lookup', 'resource' => 'customers', 'label_keys' => ['customer_number', 'company_name'], 'required' => true],
                    ['name' => 'rating', 'label' => 'Rating', 'type' => 'number', 'required' => true, 'min' => 1, 'max' => 5, 'step' => '1'],
                    ['name' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['name' => 'body', 'label' => 'Body', 'type' => 'textarea'],
                    ['name' => 'is_approved', 'label' => 'Approved', 'type' => 'bool'],
                    ['name' => 'reviewed_at', 'label' => 'Reviewed at', 'type' => 'datetime'],
                ],
            ],
        ];
    }
}
