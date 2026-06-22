<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Success.', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    protected function error(string $message = 'Error.', int $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
