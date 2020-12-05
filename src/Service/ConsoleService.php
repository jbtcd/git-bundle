<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class ConsoleService
 *
 * @author Jonah Böther
 */
class ConsoleService
{
    public function exec(
        array $processParameters
    ): array {
        $process = new Process($processParameters, getcwd() . '/../');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return explode(PHP_EOL, $process->getOutput());
    }
}
