<?php

namespace App\Domains\Reviews\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Reviews\Models\Review */
class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'product_id' => $this->product_id,
            'customer_id' => $this->customer_id,
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'is_approved' => $this->is_approved,
            'reviewed_at' => optional($this->reviewed_at)?->toIso8601String(),
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}