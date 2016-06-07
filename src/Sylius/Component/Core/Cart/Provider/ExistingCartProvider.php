<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Core\Cart\Provider;

use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Core\Repository\CartRepositoryInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ExistingCartProvider implements CartProviderInterface
{
    /**
     * @var ShopperContextInterface
     */
    private $shopperContext;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param ShopperContextInterface $shopperContext
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(ShopperContextInterface $shopperContext, CartRepositoryInterface $cartRepository)
    {
        $this->shopperContext = $shopperContext;
        $this->cartRepository = $cartRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getCart()
    {
        $customer = $this->shopperContext->getCustomer();

        if (null === $customer) {
            return null;
        }

        $channel = $this->shopperContext->getChannel();

        return $this->cartRepository->findOneByChannelAndCustomer($channel, $customer);
    }
}
