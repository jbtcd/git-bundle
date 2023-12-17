<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\DataCollector;

use DateTime;
use GitBundle\DataCollector\GitCollector;
use GitBundle\Model\CommitModel;
use GitBundle\Service\GitService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GitCollectorTest
 *
 * @author Jonah Böther
 */
class GitCollectorTest extends TestCase
{
    public function testResetFunction(): void
    {
        $gitCollector = new GitCollector(
            $this->createGitServiceMock()
        );

        $gitCollector->reset();

        $this->assertEquals(0, $gitCollector->getMaxCommits());
    }

    public function testName(): void
    {
        $gitCollector = new GitCollector(
            $this->createGitServiceMock()
        );

        $this->assertEquals('jbtcd.git', $gitCollector->getName());
    }

    public static function providerTestCollector(): array
    {
        return [
            'default dataset' => [
                'expectedResults' => [
                    'repositoryName' => null,
                    'repositoryUrl' => null,
                    'repositoryCommitUrl' => null,
                    'repositoryBranchUrl' => null,
                    'maxCommits' => 10,
                    'commitIdLength' => 7,
                    'timings' => [],
                    ],
                'parameter' => [
                    'repositoryName' => null,
                    'repositoryUrl' => null,
                    'repositoryCommitUrl' => null,
                    'repositoryBranchUrl' => null,
                    'maxCommits' => 10,
                    'commitIdLength' => 7,
                    'timings' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerTestCollector
     */
    public function testCollector(
        array $expectedResults,
        array $parameters
    ): void {
        $gitCollector = new GitCollector(
            $this->createGitServiceMock(),
            $parameters['repositoryName'],
            $parameters['repositoryUrl'],
            $parameters['repositoryCommitUrl'],
            $parameters['repositoryBranchUrl'],
            $parameters['maxCommits'],
            $parameters['commitIdLength'],
            $parameters['timings']
        );

        $gitCollector->collect(
            $this->createMock(Request::class),
            $this->createMock(Response::class)
        );

        $this->assertEquals($expectedResults['repositoryName'], $gitCollector->getRepositoryName());
        $this->assertEquals($expectedResults['repositoryUrl'], $gitCollector->getRepositoryUrl());
        $this->assertEquals($expectedResults['repositoryCommitUrl'], $gitCollector->getRepositoryCommitUrl());
        $this->assertEquals($expectedResults['repositoryBranchUrl'], $gitCollector->getBranchUrl());
        $this->assertEquals($expectedResults['maxCommits'], $gitCollector->getMaxCommits());
        $this->assertEquals($expectedResults['commitIdLength'], $gitCollector->getCommitIdLength());
        $this->assertEquals($expectedResults['timings'], $gitCollector->getTimings());
        $this->assertNotEmpty($gitCollector->getCommits());
        $this->assertNotEmpty($gitCollector->getLastCommit());
        $this->assertEquals([], $gitCollector->getBranches());
        $this->assertEquals('', $gitCollector->getCurrentBranch());
        $this->assertFalse($gitCollector->isGitRepository());
        $this->assertNotNull($gitCollector->getTime());
        $this->assertEquals('#A46A1F', $gitCollector->getColor());
    }

    private function createGitServiceMock(): MockObject
    {
        $gitServiceMock = $this->createMock(GitService::class);

        $gitServiceMock
            ->method('getCommits')
            ->willReturn(
                [
                    (new CommitModel())->setDateTime(new DateTime())
                ]
            );

        return $gitServiceMock;
    }
}
