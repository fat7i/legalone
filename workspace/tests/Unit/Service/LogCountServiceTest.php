<?php

namespace App\Tests\Unit\Service;

use App\Dto\LogCounterDto;
use App\Dto\LogFilterDto;
use App\Repository\LogRepository;
use App\Service\LogCountService;
use App\Transformer\LogCounterTransformer;
use App\Transformer\LogFilterTransformer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogCountServiceTest extends KernelTestCase
{
    public function testGetLogCount(): void
    {
        $logFilterTransformer = $this->createMock(LogFilterTransformer::class);
        $logFilterTransformer->method('transform')->willReturn(new LogFilterDto());

        $logRepository = $this->createMock(LogRepository::class);
        $logRepository->method('getLogCount')->willReturn(10);

        $logCounterTransformer = $this->createMock(LogCounterTransformer::class);
        $logCounterTransformer->method('transform')->willReturn(new LogCounterDto(10));

        $service = new LogCountService(
            $logFilterTransformer,
            $logRepository,
            $logCounterTransformer,
        );

        $logCounterDto = $service->getLogCount();

        $this->assertInstanceOf(LogCounterDto::class, $logCounterDto);
        $this->assertEquals(10, $logCounterDto->counter);
    }
}