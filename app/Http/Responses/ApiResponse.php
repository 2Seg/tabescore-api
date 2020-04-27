<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse implements Responsable
{
    public function __construct($data = '', int $status = JsonResponse::HTTP_OK, array $headers = [])
    {
        parent::__construct($data, $status, $headers);

        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'data' => $this->data,
        ]);
    }
}