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
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserRepositorySpec extends ObjectBehavior
{
    public function let(EntityRepository $objectRepository, EntityManager $objectManager, FilterCollection $filterCollection)
    {
        $objectManager->getFilters()->willReturn($filterCollection);

        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_creates_filtering_paginator(
        EntityRepository $objectRepository,
        FilterCollection $filterCollection,
        QueryBuilder $queryBuilder,
        Expr $expr,
        AbstractQuery $query
    ) {
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->expr()->willReturn($expr);
        $expr->like(Argument::any(), Argument::any())->willReturn($expr);
        $expr->eq(Argument::any(), Argument::any())->willReturn($expr);

        $queryBuilder->andWhere('o.enabled = :enabled')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('enabled', true)->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->leftJoin('o.customer', 'customer')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->where('customer.emailCanonical LIKE :query')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere('customer.firstName LIKE :query')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere('customer.lastName LIKE :query')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->orWhere('o.username LIKE :query')->shouldBeCalled()->willReturn($queryBuilder);
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

    function it_finds_details(EntityRepository $objectRepository, FilterCollection $filterCollection, QueryBuilder $queryBuilder, Expr $expr, AbstractQuery $query)
    {
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->eq('o.id', ':id')->shouldBeCalled()->willReturn($expr);

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->leftJoin('o.customer', 'customer')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->addSelect('customer')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->where($expr)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('id', 10)->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled();

        $filterCollection->enable('softdeleteable')->shouldBeCalled();

        $this->findForDetailsPage(10);
    }

    function it_counts_user_user_repository(
        EntityRepository $objectRepository,
        QueryBuilder $queryBuilder,
        \DateTime $from,
        \DateTime $to,
        AbstractQuery $query,
        Expr $expr
    ) {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->gte(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($expr);
        $expr->lte(Argument::any(), Argument::any())->shouldBeCalled()->willReturn($expr);

        $queryBuilder->andWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('from', $from)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('to', $to)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere('o.status = :status')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('status', 'status')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->select('count(o.id)')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleScalarResult()->shouldBeCalled();

        $this->countBetweenDates($from, $to, 'status');
    }

    function it_finds_one_by_email(
        $filterCollection,
        $objectRepository,
        QueryBuilder $builder,
        Expr $expr,
        AbstractQuery $query
    ) {
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($builder);

        $builder->leftJoin('o.customer', 'customer')->shouldBeCalled()->willReturn($builder);
        $builder->addSelect('customer')->shouldBeCalled()->willReturn($builder);

        $builder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->eq('o.id', ':id')->shouldBeCalled()->willReturn($expr);

        $builder->where($expr)->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('id', 10)->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getOneOrNullResult()->shouldBeCalled();

        $filterCollection->enable('softdeleteable')->shouldBeCalled();

        $this->findForDetailsPage(10);
    }
}
