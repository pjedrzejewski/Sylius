<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Sylius\Bundle\CoreBundle\Kernel\Kernel;

/**
 * Sylius application kernel.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            // Put here your own bundles
            new Astound\Bundle\AddressingBundle\AstoundAddressingBundle(),
            new Astound\Bundle\WebBundle\AstoundWebBundle(),
            new Astound\Bundle\CoreBundle\AstoundCoreBundle(),
            // new Astound\Bundle\LocationBundle\AstoundLocationBundle(),
            // new Astound\Bundle\TestBundle\AstoundTestBundle(),
        );

        return array_merge(parent::registerBundles(), $bundles);
    }
}
