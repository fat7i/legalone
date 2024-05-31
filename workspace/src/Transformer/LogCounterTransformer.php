<?php

namespace App\Transformer;

use App\Dto\LogCounterDto;

class LogCounterTransformer
{
    public function transform(int $counter): LogCounterDto
    {
        return new LogCounterDto(
            counter: $counter,
        );
    }
}