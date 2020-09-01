<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Service;

use GitBundle\Service\ConsoleService;
use GitBundle\Service\GitService;
use PHPUnit\Framework\TestCase;

/**
 * Class ConsoleServiceTest
 *
 * @author Jonah Böther
 */
class GitServiceTest extends TestCase
{
    public function testExecGitBranch(): void
    {
        $consoleServiceMock = $this->createMock(ConsoleService::class);

        $consoleServiceMock
            ->method('exec')
            ->willReturn(['* master', 'slave']);

        $gitService = new GitService(
            $consoleServiceMock
        );

        $gitService->execGitBranch();

        $this->assertTrue($gitService->hasBranches());

        $this->assertEquals(['master', 'slave'], $gitService->getBranches());

        $this->assertEquals('master', $gitService->getCurrentBranch());
    }

    public function testExecGitCommit(): void
    {
        $consoleServiceMock = $this->createMock(ConsoleService::class);

        $consoleServiceMock
            ->method('exec')
            ->willReturn([
                'commit ff0b01407ac8cd6bccae0f750f501b29aa098df3',
                'Author: Jonah Böther <mail@jbtcd.me>',
                'Date:   Fri Jul 20 18:43:26 2018 +0200',
                '    Initial commit',
            ])
            ->willReturn([
                'commit ff0b01407ac8cd6bccae0f750f501b29aa098df3',
                'Merge: a397543 7b5816f',
                'Author: Jonah Böther <mail@jbtcd.me>',
                'Date:   Fri Jul 20 18:43:26 2018 +0200',
                '    Merge branch \'create-first-tests\' into \'master\'',
            ]);

        $gitService = new GitService(
            $consoleServiceMock
        );

        $gitService->setBranches(['foo' => 'bar']);

        $gitService->execGitCommits(2);

        $this->assertEquals(2, count($gitService->getCommits()));
    }
}
