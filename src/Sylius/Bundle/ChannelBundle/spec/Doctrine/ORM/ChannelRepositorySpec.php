<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ChannelBundle\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
class ChannelRepositorySpec extends ObjectBehavior
{
    function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_implements_channel_repository_interface()
    {
        $this->shouldImplement('Sylius\Component\Channel\Repository\ChannelRepositoryInterface');
    }

    function it_finds_channel_by_host_name(EntityRepository $objectRepository, QueryBuilder $builder, AbstractQuery $query, Expr $expr)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($builder);
        $builder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->like('o.url', ':hostname')->shouldBeCalled()->willReturn($expr);

        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('hostname', '%host%')->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled();

        $this->findMatchingHostname('host');
    }
}
