<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Core\Cart\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Core\Cart\Provider\ExistingCartProvider;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\CartRepositoryInterface;

/**
 * @mixin ExistingCartProvider
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class ExistingCartProviderSpec extends ObjectBehavior
{
    function let(ShopperContextInterface $shopperContext, CartRepositoryInterface $cartRepository)
    {
        $this->beConstructedWith($shopperContext, $cartRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Core\Cart\Provider\ExistingCartProvider');
    }
    
    function it_implements_cart_provider_interface()
    {
        $this->shouldImplement(CartProviderInterface::class);
    }

    function it_returns_cart_for_given_channel_and_customer(
        ShopperContextInterface $shopperContext,
        CustomerInterface $customer,
        ChannelInterface $channel,
        CartRepositoryInterface $cartRepository,
        OrderInterface $cart
    ) {
        $shopperContext->getCustomer()->willReturn($customer);
        $shopperContext->getChannel()->willReturn($channel);

        $cartRepository->findOneByChannelAndCustomer($channel, $customer)->willReturn($cart);
        
        $this->getCart()->shouldReturn($cart);
    }

    function it_returns_null_if_cart_does_not_exist_for_given_channel_and_customer(
        ShopperContextInterface $shopperContext,
        CustomerInterface $customer,
        ChannelInterface $channel,
        CartRepositoryInterface $cartRepository,
        OrderInterface $cart
    ) {
        $shopperContext->getCustomer()->willReturn($customer);
        $shopperContext->getChannel()->willReturn($channel);

        $cartRepository->findOneByChannelAndCustomer($channel, $customer)->willReturn(null);

        $this->getCart()->shouldReturn(null);
    }

    function it_returns_null_if_customer_is_undefined(ShopperContextInterface $shopperContext) 
    {
        $shopperContext->getCustomer()->willReturn(null);
        
        $this->getCart()->shouldReturn(null);
    }
}
