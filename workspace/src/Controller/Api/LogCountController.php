<?php

namespace App\Controller\Api;

use App\Resource\error\GeneralErrorResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\RequestValidator\LogCount\GetLogCountRequestValidator;
use App\Service\LogCountService;

class LogCountController extends AbstractController
{
    public function __construct(
        private readonly LogCountService $logCountService,
    ) {
    }

    #[Route('/count', name: 'log_count', methods: ['GET'])]
    public function getCount(GetLogCountRequestValidator $requestValidator): JsonResponse
    {
        try {
            [
                'serviceNames' => $serviceNames,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'statusCode' => $statusCode,
            ] = $requestValidator->validateFields();

            $data = $this->logCountService->getLogCount(
                $serviceNames,
                $startDate,
                $endDate,
                $statusCode,
            );

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return GeneralErrorResource::response($e);
        }
    }
}