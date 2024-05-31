<?php

namespace App\Service;

use App\Transformer\LogCounterTransformer;
use App\Transformer\LogFilterTransformer;
use App\Repository\LogRepository;
use App\Dto\LogCounterDto;

readonly class LogCountService
{
    public function __construct(
        private LogFilterTransformer  $logFilterTransformer,
        private LogRepository $logRepository,
        private LogCounterTransformer $logCounterTransformer,
    )
    {
    }

    public function getLogCount(
        ?array $serviceNames = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $statusCode = null,
    ): LogCounterDto {
        $logFilterDto = $this->logFilterTransformer->transform(
            $serviceNames,
            $startDate,
            $endDate,
            $statusCode,
        );

        $counter = $this->logRepository->getLogCount($logFilterDto);

        return $this->logCounterTransformer->transform($counter);
    }
}