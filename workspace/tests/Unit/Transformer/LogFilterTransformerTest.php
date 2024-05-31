<?php

namespace App\Tests\Unit\Transformer;

use App\Dto\LogFilterDto;
use App\Transformer\LogFilterTransformer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogFilterTransformerTest extends KernelTestCase
{
    public function testTransform(): void
    {
        $transformer = new LogFilterTransformer();

        $serviceNames = [
            'test_service_name_one',
            'test_service_name_two',
            'test_service_name_three',
        ];

        $startDate = '20-10-2023';
        $endDate = '21-10-2023';
        $statusCode = 200;

        $logFilterDto = $transformer->transform(
            serviceNames: $serviceNames,
            startDate: $startDate,
            endDate: $endDate,
            statusCode: $statusCode,
        );

        $this->assertInstanceOf(LogFilterDto::class, $logFilterDto);
        $this->assertEquals($serviceNames, $logFilterDto->serviceNames);
        $this->assertInstanceOf(\DateTimeImmutable::class, $logFilterDto->startDate);
        $this->assertEquals($startDate, $logFilterDto->startDate->format('d-m-Y'));
        $this->assertInstanceOf(\DateTimeImmutable::class, $logFilterDto->endDate);
        $this->assertEquals($endDate, $logFilterDto->endDate->format('d-m-Y'));
        $this->assertEquals($statusCode, $logFilterDto->statusCode);
    }
}