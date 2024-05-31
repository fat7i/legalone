<?php

namespace App\Resource\error;

use App\Exception\ValidationErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ValidationErrorResource
{
    public static function response(ValidationErrorException $e): JsonResponse
    {
        return new JsonResponse(
            [
                'errors' => json_decode($e->getMessage(), true, 512, JSON_THROW_ON_ERROR),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
