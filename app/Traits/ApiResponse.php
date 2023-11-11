<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function success($message = null, $status = Response::HTTP_OK, $data = null, $meta = null): JsonResponse
    {
        $dataBody = [
            'message' => $message ?? 'success'
        ];

        if (! is_null($meta)) {
            $dataBody = array_merge($dataBody, [ 'meta' => $meta ]);
        }

        if (! is_null($data)) {
            $dataBody = array_merge($dataBody, [ 'data' => $data ]);
        }

        return response()->json($dataBody, $status);
    }

    public function error($message = null , $status = Response::HTTP_BAD_REQUEST, $errors = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public function authorizationError($message = null): JsonResponse
    {
        return $this->error($message ?? 'Authorization Failed', Response::HTTP_UNAUTHORIZED);
    }
}
