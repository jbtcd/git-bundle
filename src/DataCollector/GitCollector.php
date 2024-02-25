<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\DataCollector;

use GitBundle\Service\GitConsoleServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * DataCollector for the GitBundle
 */
class GitCollector extends DataCollector
{
    /**
     * @var array<string|mixed>|Data
     */
    protected array|Data $data;

    public function __construct(
        private readonly GitConsoleServiceInterface $gitService,
    ) {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $stopWatch = new Stopwatch(true);
        $stopWatch->start('git');

        $this->data['branches'] = $this->gitService->fetchGitBranches();
        $this->data['commits'] =$this->gitService->fetchGitCommitHistory(100000);
        $this->data['remotes'] =$this->gitService->fetchGitRemotes();

        $time = $stopWatch->stop('git')->getDuration();

        $this->data['branch'] = current(array_filter($this->data['branches'], function (array $data) {
            return $data['isCurrentBranch'];
        }));

        $this->data['contributors'] = array_count_values(array_column($this->data['commits'], 'email'));

        $this->data['time'] = (int)$time;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public function getName(): string
    {
        return 'git';
    }

    /**
     * @return  array<string|mixed>|Data
     */
    public function getData(): array|Data
    {
        return $this->data;
    }
}
