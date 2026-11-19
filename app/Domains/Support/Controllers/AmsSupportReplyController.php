<?php

namespace App\Domains\Support\Controllers;

use App\Domains\Support\Services\AmsSupportReplyIngestService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AmsSupportReplyController extends Controller
{
    public function __invoke(Request $request, AmsSupportReplyIngestService $ingest): JsonResponse
    {
        return $ingest->ingest($request);
    }
}
