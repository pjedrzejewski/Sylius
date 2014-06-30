<?php

/*
 * This file is part of the Sylius package.
 *
 * (c); Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\AttributeType;

use Sylius\Component\Attribute\Model\AttributeStorage;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base attribute type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
abstract class AttributeType implements AttributeTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getStorage()
    {
        return AttributeStorage::VARCHAR;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(OptionsResolverInterface $resolver)
    {
        // No configuration!
    }
}
