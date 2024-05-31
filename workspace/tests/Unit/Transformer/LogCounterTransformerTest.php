<?php

namespace App\Tests\Unit\Transformer;

use App\Dto\LogCounterDto;
use App\Transformer\LogCounterTransformer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LogCounterTransformerTest extends KernelTestCase
{
    public function testTransform(): void
    {
        $transformer = new LogCounterTransformer();

        $counter = 10;

        $logCounterDto = $transformer->transform($counter);

        $this->assertInstanceOf(LogCounterDto::class, $logCounterDto);
        $this->assertEquals(10, $logCounterDto->counter);
    }
}