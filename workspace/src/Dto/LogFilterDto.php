<?php

namespace App\Dto;

class LogFilterDto
{
    public function __construct(
        public readonly ?array $serviceNames = null,
        public readonly ?\DateTimeImmutable $startDate = null,
        public readonly ?\DateTimeImmutable $endDate = null,
        public readonly ?int $statusCode = null,
    ) {
    }
}