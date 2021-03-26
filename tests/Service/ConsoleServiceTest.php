<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Service;

use GitBundle\Service\ConsoleService;
use PHPUnit\Framework\TestCase;

/**
 * Class ConsoleServiceTest
 *
 * @author Jonah Böther
 */
class ConsoleServiceTest extends TestCase
{
    public function testExecFunction(): void
    {
        $consoleService = new ConsoleService();

        $result = $consoleService->exec(['echo', 'Hello World']);

        $this->assertEquals('Hello World', $result[0]);
    }
}
