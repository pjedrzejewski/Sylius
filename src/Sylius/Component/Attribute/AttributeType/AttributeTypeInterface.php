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

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Attribute interface.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface AttributeTypeInterface
{
    /**
     * Get name of the attribute type.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the type of database storage.
     *
     * @return string
     */
    public function getStorage();

    /**
     * Define the configuration for the attribute type.
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setConfiguration(OptionsResolverInterface $resolver);
}
