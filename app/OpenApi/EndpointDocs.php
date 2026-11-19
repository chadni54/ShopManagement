<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'UpdateProfile',
    properties: [
            new OA\Property(property: 'first_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'last_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'email', type: 'string', format: 'email'),
            new OA\Property(property: 'phone', type: 'string', maxLength: 30, nullable: true),
            new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StoreUser',
    required: ['first_name', 'last_name', 'email', 'password', 'role'], properties: [
            new OA\Property(property: 'first_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'last_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'email', type: 'string', format: 'email'),
            new OA\Property(property: 'phone', type: 'string', maxLength: 30, nullable: true),
            new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8),
            new OA\Property(property: 'role', type: 'string', enum: ['admin', 'staff', 'customer']),
            new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive', 'suspended']),
    ]
)]
#[OA\Schema(
    schema: 'UpdateUser',
    properties: [
            new OA\Property(property: 'first_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'last_name', type: 'string', maxLength: 100),
            new OA\Property(property: 'email', type: 'string', format: 'email'),
            new OA\Property(property: 'phone', type: 'string', maxLength: 30, nullable: true),
            new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, nullable: true),
            new OA\Property(property: 'role', type: 'string', enum: ['admin', 'staff', 'customer']),
            new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive', 'suspended']),
    ]
)]
#[OA\Schema(
    schema: 'StoreCustomer',
    required: ['user_id'], properties: [
            new OA\Property(property: 'user_id', type: 'string', description: 'User UUID or numeric id'),
            new OA\Property(property: 'customer_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'company_name', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'billing_address', type: 'string', nullable: true),
            new OA\Property(property: 'shipping_address', type: 'string', nullable: true),
            new OA\Property(property: 'city', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'state', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'country', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'postal_code', type: 'string', maxLength: 20, nullable: true),
            new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive']),
    ]
)]
#[OA\Schema(
    schema: 'UpdateCustomer',
    properties: [
            new OA\Property(property: 'user_id', type: 'string', description: 'User UUID or numeric id'),
            new OA\Property(property: 'customer_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'company_name', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'billing_address', type: 'string', nullable: true),
            new OA\Property(property: 'shipping_address', type: 'string', nullable: true),
            new OA\Property(property: 'city', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'state', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'country', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'postal_code', type: 'string', maxLength: 20, nullable: true),
            new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive']),
    ]
)]
#[OA\Schema(
    schema: 'StoreCategory',
    required: ['name', 'slug'], properties: [
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'parent_id', type: 'string', nullable: true, description: 'Parent category UUID or numeric id'),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
            new OA\Property(property: 'sort_order', type: 'integer', minimum: 0),
    ]
)]
#[OA\Schema(
    schema: 'UpdateCategory',
    properties: [
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'parent_id', type: 'string', nullable: true, description: 'Parent category UUID or numeric id'),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
            new OA\Property(property: 'sort_order', type: 'integer', minimum: 0),
    ]
)]
#[OA\Schema(
    schema: 'StoreBrand',
    required: ['name', 'slug'], properties: [
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'website', type: 'string', format: 'uri', maxLength: 255, nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
    ]
)]
#[OA\Schema(
    schema: 'UpdateBrand',
    properties: [
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'website', type: 'string', format: 'uri', maxLength: 255, nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
    ]
)]
#[OA\Schema(
    schema: 'StoreProduct',
    required: ['sku', 'name', 'slug', 'price'], properties: [
            new OA\Property(property: 'category_id', type: 'string', nullable: true, description: 'Category UUID or numeric id'),
            new OA\Property(property: 'brand_id', type: 'string', nullable: true, description: 'Brand UUID or numeric id'),
            new OA\Property(property: 'sku', type: 'string', maxLength: 100),
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'price', type: 'number', minimum: 0),
            new OA\Property(property: 'compare_at_price', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'cost', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'is_active', type: 'boolean'),
            new OA\Property(property: 'attributes', type: 'object', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdateProduct',
    properties: [
            new OA\Property(property: 'category_id', type: 'string', nullable: true, description: 'Category UUID or numeric id'),
            new OA\Property(property: 'brand_id', type: 'string', nullable: true, description: 'Brand UUID or numeric id'),
            new OA\Property(property: 'sku', type: 'string', maxLength: 100),
            new OA\Property(property: 'name', type: 'string', maxLength: 255),
            new OA\Property(property: 'slug', type: 'string', maxLength: 255),
            new OA\Property(property: 'description', type: 'string', nullable: true),
            new OA\Property(property: 'price', type: 'number', minimum: 0),
            new OA\Property(property: 'compare_at_price', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'cost', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'is_active', type: 'boolean'),
            new OA\Property(property: 'attributes', type: 'object', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StoreInventory',
    required: ['product_id', 'quantity'], properties: [
            new OA\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'),
            new OA\Property(property: 'quantity', type: 'integer', minimum: 0),
            new OA\Property(property: 'reserved_quantity', type: 'integer', minimum: 0),
            new OA\Property(property: 'warehouse', type: 'string', maxLength: 100),
            new OA\Property(property: 'low_stock_threshold', type: 'integer', minimum: 0),
            new OA\Property(property: 'status', type: 'string', enum: ['in_stock', 'low_stock', 'out_of_stock']),
    ]
)]
#[OA\Schema(
    schema: 'UpdateInventory',
    properties: [
            new OA\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'),
            new OA\Property(property: 'quantity', type: 'integer', minimum: 0),
            new OA\Property(property: 'reserved_quantity', type: 'integer', minimum: 0),
            new OA\Property(property: 'warehouse', type: 'string', maxLength: 100),
            new OA\Property(property: 'low_stock_threshold', type: 'integer', minimum: 0),
            new OA\Property(property: 'status', type: 'string', enum: ['in_stock', 'low_stock', 'out_of_stock']),
    ]
)]
#[OA\Schema(
    schema: 'StoreCoupon',
    required: ['code', 'type', 'value'], properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 50),
            new OA\Property(property: 'type', type: 'string', enum: ['percent', 'fixed']),
            new OA\Property(property: 'value', type: 'number', minimum: 0),
            new OA\Property(property: 'min_order_amount', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'max_uses', type: 'integer', minimum: 1, nullable: true),
            new OA\Property(property: 'used_count', type: 'integer', minimum: 0),
            new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
    ]
)]
#[OA\Schema(
    schema: 'UpdateCoupon',
    properties: [
            new OA\Property(property: 'code', type: 'string', maxLength: 50),
            new OA\Property(property: 'type', type: 'string', enum: ['percent', 'fixed']),
            new OA\Property(property: 'value', type: 'number', minimum: 0),
            new OA\Property(property: 'min_order_amount', type: 'number', minimum: 0, nullable: true),
            new OA\Property(property: 'max_uses', type: 'integer', minimum: 1, nullable: true),
            new OA\Property(property: 'used_count', type: 'integer', minimum: 0),
            new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'is_active', type: 'boolean'),
    ]
)]
#[OA\Schema(
    schema: 'StoreOrder',
    required: ['customer_id', 'items'], properties: [
            new OA\Property(property: 'customer_id', type: 'string', description: 'Customer UUID or numeric id'),
            new OA\Property(property: 'order_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'status', type: 'string', enum: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
            new OA\Property(property: 'discount', type: 'number', minimum: 0),
            new OA\Property(property: 'tax', type: 'number', minimum: 0),
            new OA\Property(property: 'shipping_amount', type: 'number', minimum: 0),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'coupon_id', type: 'string', nullable: true, description: 'Coupon UUID or numeric id'),
            new OA\Property(property: 'notes', type: 'string', nullable: true),
            new OA\Property(property: 'placed_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'items', type: 'array', items: new OA\Items(required: ['product_id', 'quantity'], properties: [new OA\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'), new OA\Property(property: 'quantity', type: 'integer', minimum: 1), new OA\Property(property: 'unit_price', type: 'number', nullable: true), new OA\Property(property: 'sku', type: 'string', nullable: true), new OA\Property(property: 'name', type: 'string', nullable: true)])),
    ]
)]
#[OA\Schema(
    schema: 'UpdateOrder',
    properties: [
            new OA\Property(property: 'status', type: 'string', enum: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled']),
            new OA\Property(property: 'discount', type: 'number', minimum: 0),
            new OA\Property(property: 'tax', type: 'number', minimum: 0),
            new OA\Property(property: 'shipping_amount', type: 'number', minimum: 0),
            new OA\Property(property: 'notes', type: 'string', nullable: true),
            new OA\Property(property: 'coupon_id', type: 'string', nullable: true, description: 'Coupon UUID or numeric id'),
    ]
)]
#[OA\Schema(
    schema: 'CancelOrder',
    properties: [
            new OA\Property(property: 'reason', type: 'string', maxLength: 500, nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StorePayment',
    required: ['order_id', 'method', 'amount'], properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'payment_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'method', type: 'string', maxLength: 50),
            new OA\Property(property: 'amount', type: 'number', minimum: 0),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed', 'failed', 'refunded']),
            new OA\Property(property: 'provider_reference', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'paid_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdatePayment',
    properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'payment_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'method', type: 'string', maxLength: 50),
            new OA\Property(property: 'amount', type: 'number', minimum: 0),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed', 'failed', 'refunded']),
            new OA\Property(property: 'provider_reference', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'paid_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StoreShipment',
    required: ['order_id'], properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'carrier', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'tracking_number', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'status', type: 'string', maxLength: 50),
            new OA\Property(property: 'shipped_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'delivered_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'address', type: 'object', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdateShipment',
    properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'carrier', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'tracking_number', type: 'string', maxLength: 100, nullable: true),
            new OA\Property(property: 'status', type: 'string', maxLength: 50),
            new OA\Property(property: 'shipped_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'delivered_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'address', type: 'object', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StoreInvoice',
    required: ['order_id', 'subtotal', 'total'], properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'invoice_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'status', type: 'string', maxLength: 50),
            new OA\Property(property: 'subtotal', type: 'number', minimum: 0),
            new OA\Property(property: 'tax', type: 'number', minimum: 0),
            new OA\Property(property: 'total', type: 'number', minimum: 0),
            new OA\Property(property: 'issued_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'due_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'pdf_path', type: 'string', maxLength: 255, nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdateInvoice',
    properties: [
            new OA\Property(property: 'order_id', type: 'string', description: 'Order UUID or numeric id'),
            new OA\Property(property: 'invoice_number', type: 'string', maxLength: 50, nullable: true),
            new OA\Property(property: 'status', type: 'string', maxLength: 50),
            new OA\Property(property: 'subtotal', type: 'number', minimum: 0),
            new OA\Property(property: 'tax', type: 'number', minimum: 0),
            new OA\Property(property: 'total', type: 'number', minimum: 0),
            new OA\Property(property: 'issued_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'due_at', type: 'string', format: 'date-time', nullable: true),
            new OA\Property(property: 'pdf_path', type: 'string', maxLength: 255, nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'StoreReview',
    required: ['product_id', 'customer_id', 'rating'], properties: [
            new OA\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'),
            new OA\Property(property: 'customer_id', type: 'string', description: 'Customer UUID or numeric id'),
            new OA\Property(property: 'rating', type: 'integer', minimum: 1),
            new OA\Property(property: 'title', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'body', type: 'string', nullable: true),
            new OA\Property(property: 'is_approved', type: 'boolean'),
            new OA\Property(property: 'reviewed_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdateReview',
    properties: [
            new OA\Property(property: 'product_id', type: 'string', description: 'Product UUID or numeric id'),
            new OA\Property(property: 'customer_id', type: 'string', description: 'Customer UUID or numeric id'),
            new OA\Property(property: 'rating', type: 'integer', minimum: 1),
            new OA\Property(property: 'title', type: 'string', maxLength: 255, nullable: true),
            new OA\Property(property: 'body', type: 'string', nullable: true),
            new OA\Property(property: 'is_approved', type: 'boolean'),
            new OA\Property(property: 'reviewed_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'UpdateSetting',
    properties: [
            new OA\Property(property: 'enable_notifications', type: 'boolean'),
            new OA\Property(property: 'enable_email', type: 'boolean'),
            new OA\Property(property: 'enable_push', type: 'boolean'),
            new OA\Property(property: 'language', type: 'string', maxLength: 10),
            new OA\Property(property: 'timezone', type: 'string', maxLength: 64),
            new OA\Property(property: 'currency', type: 'string', maxLength: 3),
            new OA\Property(property: 'preferences', type: 'object', nullable: true),
    ]
)]
#[OA\Get(
    path: '/api/v1/users',
    tags: ['Users'],
    summary: 'List users',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'name', 'email'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'role', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/users',
    tags: ['Users'],
    summary: 'Create User',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreUser')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/users/{user}',
    tags: ['Users'],
    summary: 'Show User',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, description: 'User UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/users/{user}',
    tags: ['Users'],
    summary: 'Update User',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, description: 'User UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateUser')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/users/{user}',
    tags: ['Users'],
    summary: 'Delete User',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'user', in: 'path', required: true, description: 'User UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/customers',
    tags: ['Customers'],
    summary: 'List customers',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'customer_number', 'company_name'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'user_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'country', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/customers',
    tags: ['Customers'],
    summary: 'Create Customer',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreCustomer')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/customers/{customer}',
    tags: ['Customers'],
    summary: 'Show Customer',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, description: 'Customer UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/customers/{customer}',
    tags: ['Customers'],
    summary: 'Update Customer',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, description: 'Customer UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateCustomer')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/customers/{customer}',
    tags: ['Customers'],
    summary: 'Delete Customer',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'customer', in: 'path', required: true, description: 'Customer UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/categories',
    tags: ['Categories'],
    summary: 'List categories',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'name', 'sort_order'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'is_active', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'parent_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/categories',
    tags: ['Categories'],
    summary: 'Create Category',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreCategory')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/categories/{category}',
    tags: ['Categories'],
    summary: 'Show Category',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, description: 'Category UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/categories/{category}',
    tags: ['Categories'],
    summary: 'Update Category',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, description: 'Category UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateCategory')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/categories/{category}',
    tags: ['Categories'],
    summary: 'Delete Category',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'category', in: 'path', required: true, description: 'Category UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/brands',
    tags: ['Brands'],
    summary: 'List brands',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'name'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'is_active', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/brands',
    tags: ['Brands'],
    summary: 'Create Brand',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreBrand')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/brands/{brand}',
    tags: ['Brands'],
    summary: 'Show Brand',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'brand', in: 'path', required: true, description: 'Brand UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/brands/{brand}',
    tags: ['Brands'],
    summary: 'Update Brand',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'brand', in: 'path', required: true, description: 'Brand UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateBrand')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/brands/{brand}',
    tags: ['Brands'],
    summary: 'Delete Brand',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'brand', in: 'path', required: true, description: 'Brand UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/products',
    tags: ['Products'],
    summary: 'List products',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'name', 'price', 'sku'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'category_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'brand_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_active', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'currency', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/products',
    tags: ['Products'],
    summary: 'Create Product',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreProduct')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/products/{product}',
    tags: ['Products'],
    summary: 'Show Product',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, description: 'Product UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/products/{product}',
    tags: ['Products'],
    summary: 'Update Product',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, description: 'Product UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateProduct')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/products/{product}',
    tags: ['Products'],
    summary: 'Delete Product',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, description: 'Product UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/inventory',
    tags: ['Inventory'],
    summary: 'List inventory',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'quantity', 'warehouse'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'product_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'warehouse', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/inventory',
    tags: ['Inventory'],
    summary: 'Create Inventory',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreInventory')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/inventory/{inventory}',
    tags: ['Inventory'],
    summary: 'Show Inventory',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/inventory/{inventory}',
    tags: ['Inventory'],
    summary: 'Update Inventory',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateInventory')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/inventory/{inventory}',
    tags: ['Inventory'],
    summary: 'Delete Inventory',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'inventory', in: 'path', required: true, description: 'Inventory UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/coupons',
    tags: ['Coupons'],
    summary: 'List coupons',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'code', 'starts_at', 'ends_at'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'type', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_active', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/coupons',
    tags: ['Coupons'],
    summary: 'Create Coupon',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreCoupon')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/coupons/{coupon}',
    tags: ['Coupons'],
    summary: 'Show Coupon',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', required: true, description: 'Coupon UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/coupons/{coupon}',
    tags: ['Coupons'],
    summary: 'Update Coupon',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', required: true, description: 'Coupon UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateCoupon')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/coupons/{coupon}',
    tags: ['Coupons'],
    summary: 'Delete Coupon',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'coupon', in: 'path', required: true, description: 'Coupon UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/orders',
    tags: ['Orders'],
    summary: 'List orders',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'placed_at', 'total', 'order_number'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'customer_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'currency', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'coupon_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/orders',
    tags: ['Orders'],
    summary: 'Create Order',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreOrder')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/orders/{order}',
    tags: ['Orders'],
    summary: 'Show Order',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', required: true, description: 'Order UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/orders/{order}',
    tags: ['Orders'],
    summary: 'Update Order',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', required: true, description: 'Order UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateOrder')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/orders/{order}',
    tags: ['Orders'],
    summary: 'Delete Order',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', required: true, description: 'Order UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/payments',
    tags: ['Payments'],
    summary: 'List payments',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'amount', 'paid_at'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'method', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'currency', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/payments',
    tags: ['Payments'],
    summary: 'Create Payment',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StorePayment')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/payments/{payment}',
    tags: ['Payments'],
    summary: 'Show Payment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'payment', in: 'path', required: true, description: 'Payment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/payments/{payment}',
    tags: ['Payments'],
    summary: 'Update Payment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'payment', in: 'path', required: true, description: 'Payment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdatePayment')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/payments/{payment}',
    tags: ['Payments'],
    summary: 'Delete Payment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'payment', in: 'path', required: true, description: 'Payment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/shipping',
    tags: ['Shipping'],
    summary: 'List shipping',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'shipped_at', 'delivered_at'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'carrier', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/shipping',
    tags: ['Shipping'],
    summary: 'Create Shipment',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreShipment')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/shipping/{shipping}',
    tags: ['Shipping'],
    summary: 'Show Shipment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'shipping', in: 'path', required: true, description: 'Shipment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/shipping/{shipping}',
    tags: ['Shipping'],
    summary: 'Update Shipment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'shipping', in: 'path', required: true, description: 'Shipment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateShipment')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/shipping/{shipping}',
    tags: ['Shipping'],
    summary: 'Delete Shipment',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'shipping', in: 'path', required: true, description: 'Shipment UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/invoices',
    tags: ['Invoices'],
    summary: 'List invoices',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'issued_at', 'due_at', 'total'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'order_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/invoices',
    tags: ['Invoices'],
    summary: 'Create Invoice',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreInvoice')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/invoices/{invoice}',
    tags: ['Invoices'],
    summary: 'Show Invoice',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'invoice', in: 'path', required: true, description: 'Invoice UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/invoices/{invoice}',
    tags: ['Invoices'],
    summary: 'Update Invoice',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'invoice', in: 'path', required: true, description: 'Invoice UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateInvoice')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/invoices/{invoice}',
    tags: ['Invoices'],
    summary: 'Delete Invoice',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'invoice', in: 'path', required: true, description: 'Invoice UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Get(
    path: '/api/v1/reviews',
    tags: ['Reviews'],
    summary: 'List reviews',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
            new OA\Parameter(name: 'sort_by', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['created_at', 'rating', 'reviewed_at'])),
            new OA\Parameter(name: 'sort_dir', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'])),
            new OA\Parameter(name: 'product_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'customer_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_approved', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'rating', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/reviews',
    tags: ['Reviews'],
    summary: 'Create Review',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/StoreReview')),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/reviews/{review}',
    tags: ['Reviews'],
    summary: 'Show Review',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, description: 'Review UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Record'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Put(
    path: '/api/v1/reviews/{review}',
    tags: ['Reviews'],
    summary: 'Update Review',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, description: 'Review UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateReview')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Delete(
    path: '/api/v1/reviews/{review}',
    tags: ['Reviews'],
    summary: 'Delete Review',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'review', in: 'path', required: true, description: 'Review UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
#[OA\Post(
    path: '/api/v1/orders/{order}/cancel',
    tags: ['Orders'],
    summary: 'Cancel order',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'order', in: 'path', required: true, description: 'Order UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/CancelOrder')),
        responses: [
            new OA\Response(response: 200, description: 'Cancelled'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/settings',
    tags: ['Settings'],
    summary: 'Get settings for the authenticated user',
    security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Settings'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Put(
    path: '/api/v1/settings',
    tags: ['Settings'],
    summary: 'Update settings',
    security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/UpdateSetting')),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation failed'),
    ]
)]
#[OA\Get(
    path: '/api/v1/notifications',
    tags: ['Notifications'],
    summary: 'List notifications for the authenticated user',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/notifications/read-all',
    tags: ['Notifications'],
    summary: 'Mark all notifications as read',
    security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
    ]
)]
#[OA\Post(
    path: '/api/v1/notifications/{notification}/read',
    tags: ['Notifications'],
    summary: 'Mark one notification as read',
    security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'notification', in: 'path', required: true, description: 'Notification UUID', schema: new OA\Schema(type: 'string', format: 'uuid')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Not found'),
    ]
)]
class EndpointDocs
{
}
