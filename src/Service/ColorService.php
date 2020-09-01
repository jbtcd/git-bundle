<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

use DateTime;
use DateTimeZone;
use GitBundle\Model\CommitModel;

/**
 * Class ColorService
 *
 * @author Jonah Böther
 */
class ColorService
{
    const DEFAULT_COLOR = '#A46A1F';

    /** @var array */
    private $timeConfig;

    public function __construct(
        array $timeConfig
    ) {
        $this->timeConfig = $timeConfig;
    }

    public function fetchColor(
        CommitModel $commit
    ): string {
        $minutesSinceCommit = $this->getMinutesBetweenDateTimes(
            $commit->getDateTime(),
            new DateTime(
                date('Y-m-d H:i:s', time()),
                new DateTimeZone('Europe/Berlin')
            )
        );

        foreach ($this->timeConfig as $name => $timing) {
            $min = $timing['min'] ?? 0;
            $max = $timing['max'] ?? null;

            if ($name !== 'default'
                && ($min <= $minutesSinceCommit)
                && ($max >= $minutesSinceCommit || $max === null)
            ) {
                return $timing['color'];
            }
        }

        if (!empty($this->timeConfig['default'])) {
            return $this->timeConfig['default']['color'];
        }

        return self::DEFAULT_COLOR;
    }

    private function getMinutesBetweenDateTimes(
        DateTime $dateTime,
        DateTime $compareDate
    ): int {
        $minutes = 0;

        $diff = date_diff($dateTime, $compareDate);

        $minutes += $diff->y * 365 * 24 * 60;
        $minutes += $diff->d * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;

        return $minutes;
    }
}
