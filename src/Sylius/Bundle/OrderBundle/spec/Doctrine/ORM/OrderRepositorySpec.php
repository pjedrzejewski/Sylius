<?php

namespace spec\Sylius\Bundle\OrderBundle\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

class OrderRepositorySpec extends ObjectBehavior
{
    function let(EntityRepository $entityRepository, EntityManager $entityManager)
    {
        $this->beConstructedWith($entityRepository, $entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository');
    }

    function it_is_repository()
    {
        $this->shouldImplement('Sylius\Component\Order\Repository\OrderRepositoryInterface');
    }

    function it_finds_recent_orders(
        $entityManager,
        $entityRepository,
        QueryBuilder $builder,
        AbstractQuery $query,
        FilterCollection $filterCollection,
        Expr $expr
    ) {
        $entityManager->getFilters()->willReturn($filterCollection);
        $filterCollection->disable('softdeleteable')->shouldBeCalled();

        $entityRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($builder);

        $builder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->isNotNull('o.completedAt')->willReturn($expr);

        $builder->andWhere($expr)->shouldBeCalled()->willReturn($builder);
        $builder->setMaxResults(10)->shouldBeCalled()->willReturn($builder);
        $builder->orderBy('o.completedAt', 'desc')->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled();

        $this->findRecentOrders(10);
    }

    function it_checks_is_the_number_is_used($entityRepository, QueryBuilder $builder, AbstractQuery $query)
    {
        $entityRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($builder);
        $builder->select('COUNT(o.id)')->shouldBeCalled()->willReturn($builder);
        $builder->where('o.number = :number')->shouldBeCalled()->willReturn($builder);
        $builder->setParameter('number', 10)->shouldBeCalled()->willReturn($builder);

        $builder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleScalarResult()->shouldBeCalled();

        $this->isNumberUsed(10);
    }
}
