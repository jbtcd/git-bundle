<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

/**
 * Class ConsoleService
 *
 * @author Jonah Böther
 */
class ConsoleService
{
    public function exec(
        string $command
    ): array {
        exec($command, $output);

        return $output;
    }
}
