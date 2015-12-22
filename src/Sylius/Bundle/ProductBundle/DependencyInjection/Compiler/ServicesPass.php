<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ProductBundle\DependencyInjection\Compiler;

use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $variantFactoryDefinition = $container->getDefinition('sylius.factory.product_variant');
        $variantFactoryClass = $variantFactoryDefinition->getClass();
        $variantFactoryDefinition->setClass(Factory::class);

        $decoratedVariantFactoryDefinition = new Definition($variantFactoryClass);
        $decoratedVariantFactoryDefinition
            ->addArgument($variantFactoryDefinition)
            ->addArgument(new Reference('sylius.repository.product'))
        ;

        $container->setDefinition('sylius.factory.product_variant', $decoratedVariantFactoryDefinition);
    }
}
