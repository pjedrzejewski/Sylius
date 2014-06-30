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
 * Text attribute type.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class TextType extends AbstractAttributeType
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'sylius_text';
    }
}
