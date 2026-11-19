<?php

namespace App\Domains\Invoices\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Domains\Invoices\Models\Invoice */
class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'order_id' => $this->order_id,
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
            'issued_at' => optional($this->issued_at)?->toIso8601String(),
            'due_at' => optional($this->due_at)?->toIso8601String(),
            'pdf_path' => $this->pdf_path,
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}