<?php

namespace spec\Sylius\Bundle\ProductBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Archetype\Builder\ArchetypeBuilderInterface;
use Sylius\Component\Archetype\Model\ArchetypeInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ArchetypeUpdateListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ProductBundle\EventListener\ArchetypeUpdateListener');
    }

    function let(ArchetypeBuilderInterface $builder, ObjectRepository $productRepository, ObjectManager $productManager)
    {
        $this->beConstructedWith($builder, $productRepository, $productManager);
    }

    function it_can_only_update_products_if_an_archetype_was_updated(GenericEvent $event, \stdClass $notAnArchetype, ArchetypeBuilderInterface $builder, ObjectRepository $productRepository, ObjectManager $productManager)
    {
        $event->getSubject()->willReturn($notAnArchetype);

        $productRepository->findBy(Argument::any())->shouldNotBeCalled();
        $builder->build(Argument::any())->shouldNotBeCalled();
        $productManager->persist(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow('Sylius\Component\Resource\Exception\UnexpectedTypeException')->duringOnArchetypeUpdate($event);
    }

    function it_updates_products_with_newer_attributes_added_to_their_archetypes(
        GenericEvent $event, ArchetypeInterface $archetype, ArchetypeBuilderInterface $builder, ObjectRepository $productRepository, ObjectManager $productManager, ProductInterface $productA, ProductInterface $productB
    ) {
        $event->getSubject()->willReturn($archetype);
        $productRepository->findBy(array('archetype' => $archetype))->willReturn(array($productA, $productB));

        $builder->build($productA)->shouldBeCalled();
        $builder->build($productB)->shouldBeCalled();

        $productManager->persist($productA)->shouldBeCalled();
        $productManager->persist($productB)->shouldBeCalled();

        $this->onArchetypeUpdate($event);
    }
}
