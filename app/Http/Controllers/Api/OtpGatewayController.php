<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpGatewayMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtpGatewayController extends Controller
{
    private function validateKey(Request $request): bool
    {
        $serverKey = env('OTP_GATEWAY_KEY');
        if (empty($serverKey)) {
            return true;
        }
        return $request->header('X-Gateway-Key') === $serverKey;
    }

    public function getPending(Request $request): JsonResponse
    {
        if (!$this->validateKey($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return DB::transaction(function () {
            $messages = OtpGatewayMessage::where(function ($query) {
                $query->where('status', 0)
                    ->orWhere(function ($q) {
                        $q->where('status', 1)
                          ->where('locked_at', '<', now()->subMinutes(2));
                    });
            })
            ->orderBy('id', 'asc')
            ->limit(10)
            ->lockForUpdate()
            ->get();

            if ($messages->isEmpty()) {
                return response()->json(['data' => []]);
            }

            $ids = $messages->pluck('id')->toArray();

            OtpGatewayMessage::whereIn('id', $ids)->update([
                'status' => 1,
                'locked_at' => now(),
            ]);

            return response()->json([
                'data' => $messages->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'phone' => $msg->phone,
                        'message' => $msg->message,
                    ];
                })
            ]);
        });
    }

    public function reportStatus(Request $request): JsonResponse
    {
        if (!$this->validateKey($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:sent,failed',
        ]);

        $statusValue = $request->status === 'sent' ? 2 : 3;

        OtpGatewayMessage::where('id', $request->id)->update([
            'status' => $statusValue,
            'sent_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function createTestMessage(Request $request): JsonResponse
    {
        if (!$this->validateKey($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $msg = OtpGatewayMessage::create([
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message_id' => $msg->id,
        ]);
    }
}