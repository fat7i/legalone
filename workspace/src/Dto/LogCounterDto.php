<?php

namespace App\Dto;

class LogCounterDto
{
    public function __construct(
        public readonly int $counter = 0,
    ) {
    }
}