<?php

declare(strict_types=1);

namespace Talkroute\MessageSegmentCalculator\Tests;

use PHPUnit\Framework\TestCase;
use Talkroute\MessageSegmentCalculator\SegmentCalculator;

/**
 * @internal
 *
 * @covers \Talkroute\MessageSegmentCalculator\SegmentCalculator
 */
class SegmentCalculatorTest extends TestCase
{
    public static function messages(): array
    {
        return [
            ['👨‍👩‍👧‍👧', 1, 176, false],
            ['“Everything, far from the sea, is province!” - Ernest Hemingway', 1, 1008, 441],
            [
                'Fortune, which has a great deal of power in other matters but especially in war, '
                .'can bring about great changes in a situation through very slight forces. - Julius Caesar',
                2,
                1183,
                false,
            ],
            [
                "Deep into....hmm.....let's see................( 〞), and a long dash (—)",
                2,
                1136,
                497,
            ],
            [
                "Deep into that darkness peering,\n"
                ."Long I stood there, wondering, fearing,\n"
                ."Doubting, dreaming dreams no mortals\n"
                ."Ever dared to dream before;\n"
                ."But the silence was unbroken,\n"
                ."And the stillness gave no token,\n"
                ."And the only word there spoken\n"
                ."Was the whispered word, \"Lenore!\"\n"
                ."This I whispered, and an echo\n"
                ."Murmured back the word, \"Lenore!\"\n"
                .'Merely this, and nothing more.',
                3,
                2520,
                false,
            ],
        ];
    }

    /** @dataProvider messages */
    public function testCalculatesSegmentsAndBitsCountCorrectly(string $message, int $expectedSegmentsCount, int $expectedBitsCount): void
    {
        $this->assertEquals($expectedSegmentsCount, SegmentCalculator::segmentsCount($message));
        $this->assertEquals($expectedBitsCount, SegmentCalculator::bitsCount($message));
    }

    /** @dataProvider messages */
    public function testCalculatesBitsCountCorrectlyWithSmartEncoding(
        string $message,
        int $expectedSegmentsCount,
        int $expectedBitsCount,
        int|bool $expectedBitsCountWithSmartEncoding
    ): void {
        if (!is_int($expectedBitsCountWithSmartEncoding)) {
            $expectedBitsCountWithSmartEncoding = $expectedBitsCount;
        }
        $this->assertEquals($expectedBitsCountWithSmartEncoding, SegmentCalculator::bitsCount($message, true));
    }
}
