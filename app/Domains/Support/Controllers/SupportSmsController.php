<?php

namespace App\Domains\Support\Controllers;

use App\Domains\Support\Requests\StoreSupportSmsRequest;
use App\Domains\Support\Services\SupportSmsService;
use App\Http\Controllers\Controller;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;

class SupportSmsController extends Controller
{
    public function store(StoreSupportSmsRequest $request, SupportSmsService $sms): JsonResponse
    {
        $message = $sms->receive($request->validated());

        $data = [
            'message_id' => $message->message_id,
            'status' => $message->status,
            'ams_ticket_number' => $message->ams_ticket_number,
            'ams_ticket_uuid' => $message->ams_ticket_uuid,
        ];

        if ($message->status !== 'forwarded') {
            return ApiResponse::error('AMS did not accept the support SMS.', 502, $data);
        }

        return ApiResponse::created($data, 'Support SMS forwarded to AMS');
    }
}
