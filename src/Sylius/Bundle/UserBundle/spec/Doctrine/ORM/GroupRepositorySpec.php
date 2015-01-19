<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\UserBundle\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\EntityManager;

class GroupRepositorySpec extends ObjectBehavior
{
    public function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\Doctrine\ORM\GroupRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_has_a_form_query_buidler(EntityRepository $objectRepository, QueryBuilder $queryBuilder)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $this->getFormQueryBuilder();
    }
}
