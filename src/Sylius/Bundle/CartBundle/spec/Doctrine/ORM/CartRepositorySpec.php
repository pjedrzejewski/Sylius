<?php

namespace spec\Sylius\Bundle\CartBundle\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Cart\Model\CartInterface;
use Sylius\Component\Core\Model\OrderInterface;

class CartRepositorySpec extends ObjectBehavior
{
    function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CartBundle\Doctrine\ORM\CartRepository');
    }

    function it_finds_expired_cart(
        EntityRepository $objectRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Expr $expr,
        CartInterface $cart
    ) {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->lt('o.expiresAt', ':now')->shouldBeCalled()->willReturn($expr);
        $expr->eq('o.state', ':state')->shouldBeCalled()->willReturn($expr);

        $queryBuilder->leftJoin('o.items', 'item')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->addSelect('item')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere(Argument::any())->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere(Argument::any())->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('now', Argument::type('\DateTime'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('state', OrderInterface::STATE_CART)->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getResult()->shouldBeCalled()->willReturn(array($cart));

        $this->findExpiredCarts()->shouldReturn(array($cart));
    }
}
