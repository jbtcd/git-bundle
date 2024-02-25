<?php declare(strict_types=1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\Service;

interface GitConsoleServiceInterface
{
    /**
     * @return array<array<string|bool>>
     */
    public function fetchGitBranches(): array;

    /**
     * @param int $maxCommits
     *
     * @return array<array<string>>
     */
    public function fetchGitCommitHistory(int $maxCommits): array;

    /**
     * @return array<string>
     */
    public function fetchGitRemotes(): array;
}
