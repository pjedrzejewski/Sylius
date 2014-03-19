<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ProductBundle\Form\EventListener;

use Sylius\Bundle\ProductBundle\Model\PropertyTypes;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Form event listener that builds choices for property form.
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class BuildPropertyFormChoicesListener implements EventSubscriberInterface
{
    /**
     * Form factory.
     *
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $factory
     */
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'buildChoices',
            FormEvents::PRE_SUBMIT   => 'removeChoices'
        );
    }

    /**
     * Builds choices for property form
     *
     * @param FormEvent $event
     */
    public function buildChoices(FormEvent $event)
    {
        $property = $event->getData();

        if (null === $property) {
            return;
        }

        $type = $property->getType();

        if (null !== $property->getId() && PropertyTypes::CHOICE !== $type) {
            $event->getForm()->remove('configuration');
        }
    }

    /**
     * Removes the choices from the form.
     *
     * @param FormEvent $event
     */
    public function removeChocies(FormEvent $event)
    {
        $data = $event->getData();

        if (!isset($data['type'])) {
            return;
        }

        if (PropertyTypes::CHOICE !== $type) {
            $event->getForm()->remove('configuration');
        }
    }
}
