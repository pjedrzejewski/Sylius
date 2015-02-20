<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class UserRegistrationTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Sylius\Component\User\Model\User',array('sylius'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\Form\Type\UserRegistrationType');
    }
    
    function it_has_name()
    {
        $this->getName()->shouldReturn('sylius_user_registration');
    }

    function it_builds_form(FormBuilderInterface $builder)
    {
        $builder->add('firstName', 'text', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('lastName', 'text', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('email', 'text', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('plainPassword', 'repeated', Argument::any())->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, array());
    }
}
