<?php

namespace Astound\Bundle\AddressingBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Sylius\Bundle\AddressingBundle\SyliusAddressingBundle;
/**
 * Astound Override of Sylius Bundle AddressingBundle
 */

class AstoundAddressingBundle extends SyliusAddressingBundle
{
	public function getParent()
	{
		return 'SyliusAddressingBundle';
	}

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $interfaces = array(
            'Astound\Bundle\AddressingBundle\Model\AddressInterface'    => 'sylius.model.address.class',
            'Sylius\Component\Addressing\Model\CountryInterface'    => 'sylius.model.country.class',
            'Sylius\Component\Addressing\Model\ProvinceInterface'   => 'sylius.model.province.class',
            'Sylius\Component\Addressing\Model\ZoneInterface'       => 'sylius.model.zone.class',
            'Sylius\Component\Addressing\Model\ZoneMemberInterface' => 'sylius.model.zone_member.class',
        );

        $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass('sylius_addressing', $interfaces));

        $mappings = array(
            // realpath(__DIR__.'/Resources/config/doctrine/model') => 'Sylius\Component\Addressing\Model',
            realpath(__DIR__.'/Resources/config/doctrine/model') => 'Astound\Bundle\AddressingBundle\Model'
            
        );

        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array('doctrine.orm.entity_manager'), 'sylius_addressing.driver.doctrine/orm'));
    }
}