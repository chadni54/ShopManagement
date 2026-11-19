<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'ShopDemo API',
    description: 'Enterprise ShopDemo ecommerce API for AMS integration'
)]
#[OA\Server(url: '/', description: 'Current host')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum'
)]
#[OA\Tag(name: 'Health', description: 'Health checks')]
#[OA\Tag(name: 'Auth', description: 'Authentication')]
#[OA\Tag(name: 'Users', description: 'User accounts')]
#[OA\Tag(name: 'Customers', description: 'Customers')]
#[OA\Tag(name: 'Categories', description: 'Product categories')]
#[OA\Tag(name: 'Brands', description: 'Product brands')]
#[OA\Tag(name: 'Products', description: 'Products')]
#[OA\Tag(name: 'Inventory', description: 'Inventory items')]
#[OA\Tag(name: 'Coupons', description: 'Coupons')]
#[OA\Tag(name: 'Orders', description: 'Orders')]
#[OA\Tag(name: 'Payments', description: 'Payments')]
#[OA\Tag(name: 'Shipping', description: 'Shipments')]
#[OA\Tag(name: 'Invoices', description: 'Invoices')]
#[OA\Tag(name: 'Reviews', description: 'Product reviews')]
#[OA\Tag(name: 'Settings', description: 'Account settings')]
#[OA\Tag(name: 'Notifications', description: 'In-app notifications')]
class OpenApiSpec {}
