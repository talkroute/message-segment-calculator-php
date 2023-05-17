<?php
declare(strict_types=1);

namespace Talkroute\MessageSegmentCalculator;

class SegmentCalculator
{
    public function __construct(private string $message)
    {
    }
}