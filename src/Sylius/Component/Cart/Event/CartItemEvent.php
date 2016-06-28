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

use Sylius\Component\Order\Model\OrderItemInterface;
use Sylius\Component\Resource\Event\ResourceEvent;

/**
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class CartItemEvent extends ResourceEvent
{
    /**
     * @var OrderItemInterface
     */
    private $item;

    /**
     * @param OrderItemInterface $item
     */
    public function __construct(OrderItemInterface $item)
    {
        parent::__construct($item);

        $this->item = $item;
    }

    /**
     * @return OrderItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }
}
