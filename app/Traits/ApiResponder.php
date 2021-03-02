<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponder
{

    /**
     * Build a success response
     * @param string|array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data], $code);

    }

    /**
     * Build error responses
     * @param string|array $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse($message, int $code): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}
