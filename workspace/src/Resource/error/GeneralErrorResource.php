<?php

namespace App\Resource\error;

use App\Exception\ValidationErrorException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GeneralErrorResource
{
    public static function response(Exception $e): JsonResponse
    {
        if ($e instanceof ValidationErrorException) {
            return ValidationErrorResource::response($e);
        }

        return new JsonResponse(
            [
                'error' => 'Unexpected Error',
                'message' => $e->getMessage(),
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
