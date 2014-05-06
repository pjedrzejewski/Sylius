<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Store\Model;

/**
 * This interface should be implemented by objects having a relation to
 * multiple stores.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface StoresAwareInterface
{
    /**
     * Get store.
     *
     * @return null|StoreInterface
     */
    public function getStore();

    /**
     * Get store.
     *
     * @return null|StoreInterface
     */
    public function setStore(StoreInterface $store = null);
}
