<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\DataCollector;

use GitBundle\Model\CommitModel;
use GitBundle\Service\ColorService;
use GitBundle\Service\GitService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * DataCollector for the GitBundle
 */
class GitCollector extends DataCollector
{
    private GitService $gitService;
    protected $data;

    public function __construct(
        GitService $gitService,
        string $repositoryName = null,
        string $repositoryUrl = null,
        string $repositoryCommitUrl = null,
        string $repositoryBranchUrl = null,
        int $maxCommits = 10,
        int $commitIdLength = 7,
        array $timings = []
    ) {
        $this->gitService = $gitService;

        $this->data['repositoryName'] = $repositoryName;
        $this->data['repositoryUrl'] = $repositoryUrl;
        $this->data['maxCommits'] = $maxCommits;
        $this->data['commitIdLength'] = $commitIdLength;
        $this->data['repositoryCommitUrl'] = $repositoryCommitUrl;
        $this->data['repositoryBranchUrl'] = $repositoryBranchUrl;
        $this->data['timings'] = $timings;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $stopWatch = new Stopwatch(true);
        $stopWatch->start('jbtcd.git');

        $this->gitService->execGitBranch();
        $this->gitService->execGitCommits($this->getMaxCommits());

        $this->data['hasBranches'] = $this->gitService->hasBranches();
        $this->data['currentBranch'] = $this->gitService->getCurrentBranch();
        $this->data['branches'] = $this->gitService->getBranches();
        $this->data['commits'] = $this->gitService->getCommits();

        if (!empty($this->getCommits())) {
            $colorService = new ColorService($this->data['timings']);

            $this->data['color'] = $colorService->fetchColor($this->getLastCommit());
        }

        $time = $stopWatch->stop('jbtcd.git')->getDuration();

        $this->data['time'] = (int)$time;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'jbtcd.git';
    }

    public function getMaxCommits(): int
    {
        return $this->data['maxCommits'] ?? 0;
    }

    public function isGitRepository(): bool
    {
        return $this->data['hasBranches'] ?? false;
    }

    public function getTime(): int
    {
        return $this->data['time'] ?? 0;
    }

    public function getRepositoryName(): string
    {
        return $this->data["repositoryName"] ?? '';
    }

    public function getRepositoryUrl(): string
    {
        return $this->data["repositoryUrl"] ?? '';
    }

    public function getRepositoryCommitUrl(): string
    {
        return $this->data["repositoryCommitUrl"] ?? '';
    }

    public function getBranchUrl(): string
    {
        return $this->data["repositoryBranchUrl"] ?? '';
    }

    public function getCommitIdLength(): int
    {
        return $this->data['commitIdLength'] ?? 0;
    }

    public function getCurrentBranch(): string
    {
        return $this->data['currentBranch'] ?? '';
    }

    public function getBranches(): array
    {
        return $this->data['branches'] ?? [];
    }

    public function getCommits(): array
    {
        return $this->data['commits'] ?? [];
    }

    public function getLastCommit(): ?CommitModel
    {
        return $this->data['commits'] ? $this->data['commits'][0] : null;
    }

    public function getTimings(): array
    {
        return $this->data['timings'] ?? [];
    }

    public function getColor(): string
    {
        return $this->data['color'] ?? '';
    }
}
