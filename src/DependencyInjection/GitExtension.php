<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Extension class for GitBundle
 *
 * @author Jonah Böther
 */
class GitExtension extends Extension
{
    public function load(
        array $configs,
        ContainerBuilder $container
    ): void {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('repositoryName', $config['repositoryName']);
        $container->setParameter('repositoryUrl', $config['repositoryUrl']);
        $container->setParameter('maxCommits', $config['maxCommits']);
        $container->setParameter('commitIdLength', $config['commitIdLength']);
        $container->setParameter('repositoryCommitUrl', $config['repositoryCommitUrl']);
        $container->setParameter('repositoryBranchUrl', $config['repositoryBranchUrl']);
        $container->setParameter('timings', $config['timings']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('git.yaml');
    }
}
