<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Cart\Event;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Event\ResourceEvent;

/**
 * @author Joseph Bielawski <stloyd@gmail.com>
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class CartEvent extends ResourceEvent
{
    /**
     * @var OrderInterface
     */
    private $cart;

    /**
     * @param OrderInterface $cart
     */
    public function __construct(OrderInterface $cart)
    {
        parent::__construct($cart);

        $this->cart = $cart;
    }

    /**
     * @return OrderInterface
     */
    public function getCart()
    {
        return $this->cart;
    }
}
