<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Service;

use DateTime;
use DateTimeZone;
use GitBundle\Model\CommitModel;
use GitBundle\Service\ColorService;
use PHPUnit\Framework\TestCase;

/**
 * Class GitDataCollectorTest
 *
 * @author Jonah Böther
 */
class ColorServiceTest extends TestCase
{
    public function dateProviderColorPicker(): array
    {
        return [
            'Test fallback color, if the config is empty' => [
                'config' => [],
                'minutesSinceCommit' => 50,
                'expectedResult' => '#A46A1F',
            ],
            'Test open end time range' => [
                'config' => [
                    'openEnd' => [
                        'color' => '#123',
                        'min' => 10,
                    ],
                ],
                'minutesSinceCommit' => 50,
                'expectedResult' => '#123',
            ],
            'Test open start time range' => [
                'config' => [
                    'openStart' => [
                        'color' => '#123',
                        'max' => 50,
                    ],
                ],
                'minutesSinceCommit' => 25,
                'expectedResult' => '#123',
            ],
            'Test complete time range' => [
                'config' => [
                    'completeTimeRange' => [
                        'color' => '#123',
                        'min' => 25,
                        'max' => 50,
                    ],
                ],
                'minutesSinceCommit' => 37.5,
                'expectedResult' => '#123',
            ],
            'Test fallback if last Commit not in complete time range' => [
                'config' => [
                    'completeTimeRange' => [
                        'color' => '#123',
                        'min' => 25,
                        'max' => 50,
                    ],
                ],
                'minutesSinceCommit' => 100,
                'expectedResult' => '#A46A1F',
            ],
            'Test default from config' => [
                'config' => [
                    'default' => [
                        'color' => '#123',
                        'min' => 25,
                        'max' => 50,
                    ],
                ],
                'minutesSinceCommit' => 100,
                'expectedResult' => '#123',
            ],
        ];
    }

    /**
     * @dataProvider dateProviderColorPicker
     */
    public function testColorPicker(
        array $config,
        int $minutesSinceCommit,
        string $expectedResult
    ): void {
        $colorService = new ColorService($config);

        $commitModel = new CommitModel();

        $dateTime = new DateTime(
            date('Y-m-d H:i:s', time() - $minutesSinceCommit * 60),
            new DateTimeZone('Europe/Berlin')
        );

        $commitModel->setDateTime($dateTime);

        $color = $colorService->fetchColor($commitModel);

        $this->assertEquals($expectedResult, $color);
    }
}
