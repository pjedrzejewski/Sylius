<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\DependencyInjection\Driver;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Metadata\ResourceMetadataInterface;

/**
 * @mixin \Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DatabaseDriverFactory
 *
 * @author Arnaud Langlade <aRn0D.dev@gmail.com>
 */
class DatabaseDriverFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DatabaseDriverFactory');
    }

    function it_creates_a_orm_driver_by_default(ResourceMetadataInterface $metadata)
    {
        $metadata->getDriver()->shouldBeCalled()->willReturn(SyliusResourceBundle::DRIVER_DOCTRINE_ORM);

        $this::getForResource($metadata)->shouldhaveType('Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DoctrineORMDriver');
    }

    function it_creates_a_odm_driver(ResourceMetadataInterface $metadata)
    {
        $metadata->getDriver()->shouldBeCalled()->willReturn(SyliusResourceBundle::DRIVER_DOCTRINE_MONGODB_ODM);

        $this::getForResource($metadata)->shouldhaveType('Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DoctrineODMDriver');
    }

    function it_creates_a_phpcr_driver(ResourceMetadataInterface $metadata)
    {
        $metadata->getDriver()->shouldBeCalled()->willReturn(SyliusResourceBundle::DRIVER_DOCTRINE_PHPCR_ODM);

        $this::getForResource($metadata)->shouldhaveType('Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\DoctrinePHPCRDriver');
    }
}
