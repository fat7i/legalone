<?php

namespace App\Transformer;

use App\Dto\LogFilterDto;

class LogFilterTransformer
{
    public function transform(
        ?array $serviceNames,
        ?string $startDate,
        ?string $endDate,
        ?int $statusCode,
    ): LogFilterDto
    {
        return new LogFilterDto(
            serviceNames: $serviceNames,
            startDate: $startDate ? $this->createDateTimeImmutable($startDate . '00:00:00') : null,
            endDate: $endDate ? $this->createDateTimeImmutable($endDate . '23:59:59') : null,
            statusCode: $statusCode > 0 ? $statusCode : null,
        );
    }

    private function createDateTimeImmutable(string $date): ?\DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $date);
    }
}