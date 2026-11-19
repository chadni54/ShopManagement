<?php

namespace App\Domains\Invoices\Repositories;

use App\Domains\Invoices\Models\Invoice;
use App\Shared\Repositories\BaseRepository;

class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }
}