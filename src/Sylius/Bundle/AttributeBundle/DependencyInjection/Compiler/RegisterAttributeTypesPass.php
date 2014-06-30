<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AttributeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers all attribute types in the container.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class RegisterAttributeTypesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sylius.registry.attribute_type')) {
            return;
        }

        $registry = $container->getDefinition('sylius.registry.attribute_type');
        $types = array();

        foreach ($container->findTaggedServiceIds('sylius.attribute_type') as $id => $attributes) {
            if (!isset($attributes[0]['type']) || !isset($attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged attribute type needs to have `type` and `label` attributes.');
            }

            $types[$attributes[0]['type']] = $attributes[0]['label'];

            $registry->addMethodCall('register', array($attributes[0]['type'], new Reference($id)));
        }

        $container->setParameter('sylius.attribute_types', $types);
    }
}
