<?php

/*
 * This file is part of the Sylius package.
 *
 * (c); Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\Model;

/**
 * Default attribute storages.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AttributeStorage
{
    const DATE       = 'date';
    const DATETIME   = 'datetime';
    const DECIMAL    = 'decimal';
    const BOOLEAN    = 'boolean';
    const INTEGER    = 'integer';
    const TEXT       = 'text';
    const VARCHAR    = 'varchar';
}
