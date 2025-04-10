<?php

namespace App\Traits;

use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiResponseTrait
{
    protected function successResponse(mixed $data, int $statusCode = 200, array $groups = []): JsonResponse
    {
        return $this->json([
            'status' => 'success',
            'data' => $data
        ], $statusCode, [], ['groups' => $groups]);
    }

    protected function errorResponse(string $message, int $statusCode = 400): JsonResponse
    {
        return $this->json([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }

    protected function handleApiException(Exception $e): JsonResponse
    {
        if ($e instanceof HttpException) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        } elseif ($e instanceof InvalidArgumentException) {
            return $this->errorResponse($e->getMessage(), 400);
        } else {
            return $this->errorResponse('An error occurred: ' . $e->getMessage(), 500);
        }
    }
}
