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
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
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
    function let(CanonicalizerInterface $canonicalizer, PasswordUpdaterInterface $passwordUpdater)
    {
        $this->beConstructedWith($canonicalizer, $passwordUpdater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\EventListener\UserRegisterListener');
    }

    function it_adds_data_to_user_model($canonicalizer, $passwordUpdater, GenericEvent $event, UserInterface $user)
    {
        $event->getSubject()->willReturn($user);

        $user->getUsername()->willReturn('testUser');
        $user->getEmail()->willReturn('test@user.com');
        $user->setUsernameCanonical('testuser')->shouldBeCalled();
        $user->setEmailCanonical('test@user.com')->shouldBeCalled();

        $canonicalizer->canonicalize('testUser')->willReturn('testuser');
        $canonicalizer->canonicalize('test@user.com')->willReturn('test@user.com');
        
        $passwordUpdater->updatePassword($user)->shouldBeCalled();

        $this->preUserRegistration($event);
    }
}
