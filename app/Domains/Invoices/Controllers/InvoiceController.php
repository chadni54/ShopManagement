<?php

namespace App\Domains\Invoices\Controllers;

use App\Domains\Invoices\Models\Invoice;
use App\Domains\Invoices\Requests\StoreInvoiceRequest;
use App\Domains\Invoices\Requests\UpdateInvoiceRequest;
use App\Domains\Invoices\Resources\InvoiceResource;
use App\Domains\Invoices\Services\InvoiceService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->service->list($request);

        return ApiResponse::success(
            InvoiceResource::collection($paginator),
            'Invoice list retrieved'
        );
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return ApiResponse::created(
            new InvoiceResource($model),
            'Invoice created successfully'
        );
    }

    public function show(Invoice $invoice): JsonResponse
    {
        $model = $this->service->show($invoice);

        return ApiResponse::success(
            new InvoiceResource($model),
            'Invoice retrieved'
        );
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $model = $this->service->update($invoice, $request->validated());

        return ApiResponse::success(
            new InvoiceResource($model),
            'Invoice updated successfully'
        );
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        $this->service->delete($invoice);

        return ApiResponse::noContent('Invoice deleted successfully');
    }
}