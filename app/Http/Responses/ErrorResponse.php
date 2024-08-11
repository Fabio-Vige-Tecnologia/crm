<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ErrorResponse
{
    public static function make(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'data' => [
                'error' => $message,
            ],
        ], $statusCode);
    }
}
