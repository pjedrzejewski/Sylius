<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ReportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sylius_report');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('engine')->defaultValue('twig')->cannotBeEmpty()->end()
            ->end()
        ;

        $this->addClassesSection($rootNode);
        $this->addValidationGroupsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds `validation_groups` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addValidationGroupsSection(ArrayNodeDefinition $node)
    {
        // $node
        //     ->children()
        //         ->arrayNode('validation_groups')
        //             ->addDefaultsIfNotSet()
        //             ->children()
        //                 ->arrayNode('shipping_category')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_method')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_rule_item_count_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_calculator_flat_rate_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_calculator_per_item_rate_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_calculator_flexible_rate_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                 ->arrayNode('shipping_calculator_weight_rate_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //                     ->arrayNode('shipping_calculator_volume_rate_configuration')
        //                     ->prototype('scalar')->end()
        //                     ->defaultValue(array('sylius'))
        //                 ->end()
        //             ->end()
        //         ->end()
        //     ->end()
        // ;
    }

    /**
     * Adds `classes` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addClassesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('report')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue('Sylius\Component\Report\Model\Report')->end()
                                ->scalarNode('controller')->defaultValue('Sylius\Bundle\ReportBundle\Controller\ReportController')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Sylius\Bundle\ReportBundle\Form\Type\ReportType')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
