<?php declare(strict_types = 1);

/**
 * (c) Jonah Böther <mail@jbtcd.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Provides configuration fot GitBundle
 *
 * @author Jonah Böther
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('git');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('timings')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('color')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->integerNode('min')
                                ->defaultNull()
                            ->end()
                            ->integerNode('max')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('repositoryName')
                    ->defaultNull()
                ->end()
                ->scalarNode('repositoryUrl')
                    ->defaultNull()
                ->end()
                ->integerNode('maxCommits')
                    ->defaultValue(10)
                ->end()
                ->integerNode('commitIdLength')
                    ->defaultValue(7)
                ->end()
                ->scalarNode('repositoryCommitUrl')
                    ->defaultNull()
                ->end()
                ->scalarNode('repositoryBranchUrl')
                    ->defaultNull()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
