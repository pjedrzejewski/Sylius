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

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CustomerRepositorySpec extends ObjectBehavior
{
    public function let(EntityRepository $objectRepository, EntityManager $objectManager, FilterCollection $filterCollection)
    {
        $objectManager->getFilters()->willReturn($filterCollection);

        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\Doctrine\ORM\CustomerRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_finds_details(EntityRepository $objectRepository, FilterCollection $filterCollection, QueryBuilder $queryBuilder, Expr $expr, AbstractQuery $query)
    {
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->eq('o.id', ':id')->shouldBeCalled()->willReturn($expr);

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere($expr)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('id', 1)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled();

        $filterCollection->enable('softdeleteable')->shouldBeCalled();

        $this->findForDetailsPage(1);
    }

    function it_creates_paginator(
        EntityRepository $objectRepository,
        FilterCollection $filterCollection,
        QueryBuilder $queryBuilder,
        Expr $expr,
        AbstractQuery $query
    ) {
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->leftJoin('o.user', 'user')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->expr()->willReturn($expr);
        $expr->like(Argument::any(), Argument::any())->willReturn($expr);
        $expr->eq(Argument::any(), Argument::any())->willReturn($expr);

        $queryBuilder->andWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('enabled', true)->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->where(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('query', '%arnaud%')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->addOrderBy('o.name', 'asc')->shouldBeCalled();
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);

        $this->createFilterPaginator(
            array(
                'enabled' => true,
                'query' => 'arnaud'
            ),
            array('name' => 'asc'),
            true
        )->shouldHaveType('Pagerfanta\Pagerfanta');
    }
}
