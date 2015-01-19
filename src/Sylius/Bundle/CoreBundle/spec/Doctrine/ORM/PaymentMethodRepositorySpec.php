<?php

namespace spec\Sylius\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

class PaymentMethodRepositorySpec extends ObjectBehavior
{
    public function let(EntityRepository $objectRepository, EntityManager $objectManager)
    {
        $this->beConstructedWith($objectRepository, $objectManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentMethodRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Component\Resource\Repository\ResourceRepositoryInterface');
    }

    function it_implements_payment_method_interface()
    {
        $this->shouldImplement('Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface');
    }

    function it_creates_query_builder_for_the_payment_method(
        EntityRepository $objectRepository,
        QueryBuilder $queryBuilder,
        ChannelInterface $channel,
        ArrayCollection $paymentMethods,
        PaymentMethodInterface $paymentMethod
    ) {
        $objectRepository->createQueryBuilder('o')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->andWhere('o IN (:methods)')->shouldBeCalled()->willReturn($queryBuilder);

        $channel->getPaymentMethods()->shouldBeCalled()->willReturn($paymentMethods);
        $paymentMethods->toArray()->shouldBeCalled()->willReturn(array($paymentMethod));
        $queryBuilder->setParameter('methods', array($paymentMethod))->shouldBeCalled()->willReturn($queryBuilder);

        $this->getQueryBuidlerForChoiceType(array(
            'channel' => $channel,
            'disabled' => true,
        ))->shouldReturn($queryBuilder);
    }
}
