<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Product\Factory;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\TranslatableFactoryInterface;

interface ProductFactoryInterface extends TranslatableFactoryInterface
{
    public function createWithVariant(): ProductInterface;
    public function createFromFamily($familyCode): ProductInterface;
}
