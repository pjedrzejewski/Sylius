<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Store\Context;

/**
 * This interface should be implemented by the service
 * responsible for retrieving the currently active store.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface StoreContextInterface
{
    public function getDefaultStore();
    public function setDefaultStore(StoreInterface $store);
    public function getCurrentStore();
    public function setCurrentStore(StoreInterface $store);
}
