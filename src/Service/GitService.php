<?php

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

use DateTime;
use GitBundle\Model\CommitModel;

/**
 * Class GitService
 *
 * @author Jonah Böther
 */
class GitService
{
    private ConsoleService $consoleService;

    private array $branches;
    private string $currentBranch;
    private array $commits;

    public function __construct(
        ConsoleService $consoleService
    ) {
        $this->consoleService = $consoleService;
    }

    public function execGitBranch(): void
    {
        $data = $this->consoleService->exec('git branch');

        foreach ($data as $branch) {
            $isMaster = (substr($branch, 0, 2) === '* ');

            if ($isMaster === true) {
                $currentBranch = substr($branch, strpos($branch, '* ') + 2);
                $this->currentBranch = $currentBranch;
                $this->branches[] = $currentBranch;

                continue;
            }

            $this->branches[] = $branch;
        }
    }

    public function execGitCommits(
        int $maxCommits
    ): void {
        if ($this->hasBranches()) {
            for ($i = 0; $i < $maxCommits; $i++) {
                    $command = 'git log --skip ' . $i . ' -n 1';

                $data = $this->consoleService->exec($command);

                if (!empty($data)) {
                    $commit = $this->readCommitData($data);

                    $this->commits[] = $commit;
                }
            }
        }
    }

    private function readCommitData(
        array $data
    ): CommitModel {
        $commit = new CommitModel();

        foreach ($data as $line) {
            if (strpos($line, 'commit') === 0) {
                $commit->setCommitId(substr($line, 7));
            } elseif (strpos($line, 'Author') === 0) {
                $author = [];
                preg_match('$Author: ([^<]+)<(.+)>$', $line, $author);
                if (isset($author[1])) {
                    $commit->setAuthor($author[1]);
                }
                if (isset($author[2])) {
                    $commit->setEmail($author[2]);
                }

                continue;
            } elseif (strpos($line, 'Date') === 0) {
                $date = trim(substr($line, 5)); // ddd mmm n hh:mm:ss yyyy +gmt

                $commit->setDateTime(new DateTime($date));

                continue;
            } elseif (strpos($line, 'Merge') === 0) {
                $commit->setIsMerge(true);

                continue;
            }

            $commit->setMessage(trim($line));
        }

        return $commit;
    }

    public function hasBranches(): bool
    {
        return empty($this->branches) ? false : true;
    }

    public function getBranches(): array
    {
        return $this->branches;
    }

    public function setBranches(array $branches): self
    {
        $this->branches = $branches;

        return $this;
    }

    public function getCurrentBranch(): string
    {
        return $this->currentBranch;
    }

    public function getCommits(): array
    {
        return $this->commits;
    }
}
