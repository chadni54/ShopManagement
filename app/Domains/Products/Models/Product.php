<?php

namespace App\Domains\Products\Models;

use App\Domains\Brands\Models\Brand;
use App\Domains\Categories\Models\Category;
use App\Domains\Inventory\Models\InventoryItem;
use App\Domains\Reviews\Models\Review;
use App\Shared\Traits\Auditable;
use App\Shared\Traits\HasUuid;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Auditable;
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'category_id',
        'brand_id',
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'cost',
        'currency',
        'is_active',
        'extra_attributes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'cost' => 'decimal:2',
            'is_active' => 'boolean',
            'extra_attributes' => 'array',
        ];
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
