<?php

namespace App\Traits\V1;

trait sendResponse
{
    private function sendResponse(bool $success, string $message, $data = [], $errors = null, int $code)
    {
        return response()->json([
            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $code);
    }
}
