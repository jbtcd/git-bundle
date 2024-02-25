<?php declare(strict_types=1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitConsoleService implements GitConsoleServiceInterface
{
    /**
     * @param KernelInterface $kernel
     */
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {}

    /**
     * @inheritdoc
     */
    public function fetchGitBranches(): array
    {
        $branches = [];

        $cliCommandOutput = $this->executeCliCommand(['git', 'branch']);

        foreach ($cliCommandOutput as $line) {
            if (empty($line)) {
                continue;
            }

            $branch = [
                'isCurrentBranch' => false,
            ];

            if (str_starts_with($line, '*')) {
                $branch['isCurrentBranch'] = true;

                // Remove current branch mark
                $line = ltrim($line, '* ');
            }

            $branch['name'] = $line;

            $branches[] = $branch;
        }

        return $branches;
    }

    /**
     * @inheritdoc
     */
    public function fetchGitCommitHistory(int $maxCommits): array
    {
        $commits = [];

        $cliCommandOutput = $this->executeCliCommand(['git', 'log', '-n', $maxCommits, '--pretty=format:"---GIT BUNDLE COMMIT START---%n%H%n%ct%n%cn%n%ce%n%s%n%d%n---GIT BUNDLE COMMIT END---"']);

        $commitDataArray = array_chunk($cliCommandOutput, 8);

        foreach ($commitDataArray as $commitData) {
            $data = [
                'ref' => $commitData[1],
                'time' => (int)$commitData[2],
                'author' => $commitData[3],
                'email' => $commitData[4],
                'message' => $commitData[5],
                'head' => $commitData[6],
            ];

            $commits[] = $data;
        }

        return $commits;
    }

    /**
     * @inheritdoc
     */
    public function fetchGitRemotes(): array
    {
        $remotes = [];

        $cliCommandOutput = $this->executeCliCommand(['git', 'remote', '-v']);

        foreach ($cliCommandOutput as $remoteData) {
            if (!empty($remoteData)) {
                $remotes[] = $remoteData;
            }
        }

        return $remotes;
    }

    /**
     * @param array<string> $processParameters
     *
     * @return array<string>
     */
    private function executeCliCommand(array $processParameters): array
    {
        $process = new Process($processParameters, $this->kernel->getProjectDir());
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return explode(PHP_EOL, $process->getOutput());
    }
}
