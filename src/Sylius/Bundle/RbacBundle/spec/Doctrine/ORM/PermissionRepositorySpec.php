<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\RbacBundle\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Rbac\Model\PermissionInterface;

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
class PermissionRepositorySpec extends ObjectBehavior
{
    function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\RbacBundle\Doctrine\ORM\PermissionRepository');
    }

    function it_gets_child_permissions(
        EntityRepository $objectRepository,
        PermissionInterface $permission,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Expr $expr
    ) {

        $permission->getRight()->shouldBeCalled()->willReturn(1);
        $permission->getLeft()->shouldBeCalled()->willReturn(2);

        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $expr->lt('o.left', 1)->shouldBeCalled()->willReturn($expr);
        $expr->gt('o.left', 2)->shouldBeCalled()->willReturn($expr);

        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->where(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere(Argument::type('Doctrine\ORM\Query\Expr'))->shouldBeCalled()->willReturn($queryBuilder);

        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->execute()->shouldBeCalled();

        $this->getChildPermissions($permission);
    }
}
