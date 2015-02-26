<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\User\Security;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class TokenGeneratorSpec extends ObjectBehavior
{
    function let(RepositoryInterface $repository, EntityManagerInterface $manager)
    {
        $this->beConstructedWith($repository, $manager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\User\Security\TokenGenerator');
    }

    function it_implements_user_login_interface()
    {
        $this->shouldImplement('Sylius\Component\User\Security\TokenGeneratorInterface');
    }

    function it_generates_random_token($repository, $manager)
    {
        $manager->getFilters()->disable('softdeleteable')->shouldBeCalled();

        $repository->findOneBy(array('code' => Argument::any()))->shouldBeCalled();

        $manager->getFilters()->enable('softdeleteable')->shouldBeCalled();

        $this->generateUniqueToken();
    }
}
