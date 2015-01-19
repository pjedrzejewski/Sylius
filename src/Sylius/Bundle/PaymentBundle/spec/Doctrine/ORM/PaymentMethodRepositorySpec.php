<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\PaymentBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentMethodRepositorySpec extends ObjectBehavior
{
    public function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\PaymentBundle\Doctrine\ORM\PaymentMethodRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_implements_payment_method_repository_interface()
    {
        $this->shouldImplement('Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface');
    }

    function it_creates_query_builder_for_enabled_status(EntityRepository $objectRepository, QueryBuilder $queryBuilder)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->where('o.enabled = true')->shouldBeCalled()->willReturn($queryBuilder);

        $this->getQueryBuidlerForChoiceType(array(
            'disabled' => false
        ))->shouldReturn($queryBuilder);
    }

    function it_creates_query_builder_for_all_statuses(EntityRepository $objectRepository, QueryBuilder $queryBuilder)
    {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);

        $this->getQueryBuidlerForChoiceType(array(
            'disabled' => true
        ))->shouldReturn($queryBuilder);
    }
}
