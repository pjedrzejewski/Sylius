<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\UserBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sylius\Component\User\Security\PasswordUpdaterInterface;

/**
 * User register listener spec.
 *
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class UserRegisterListenerSpec extends ObjectBehavior
{
    function let(PasswordUpdaterInterface $passwordUpdater)
    {
        $this->beConstructedWith($passwordUpdater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\EventListener\UserRegisterListener');
    }

    function it_updates_user_password($passwordUpdater, GenericEvent $event, UserInterface $user)
    {
        $event->getSubject()->willReturn($user);
        
        $passwordUpdater->updatePassword($user)->shouldBeCalled();

        $this->preUserRegistration($event);
    }
}
