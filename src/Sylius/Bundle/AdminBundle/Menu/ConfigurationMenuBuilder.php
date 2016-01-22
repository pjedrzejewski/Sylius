<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AdminBundle\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class ConfigurationMenuBuilder extends AbstractAdminMenuBuilder
{
    const EVENT_NAME = 'sylius.menu.admin.configuration';

    /**
     * @return ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');

        $this->addTaxesMenu($menu);
        $this->addShippingMenu($menu);

        $this->eventDispatcher->dispatch(self::EVENT_NAME, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     */
    private function addTaxesMenu(ItemInterface $menu)
    {
        $child = $menu
            ->addChild('taxes')
            ->setLabel('sylius.menu.admin.configuration.taxes.header')
        ;

        $child
            ->addChild('tax_categories', ['route' => 'sylius_admin_configuration'])
            ->setLabel('sylius.menu.admin.configuration.system.tax_categories')
            ->setLabelAttribute('icon', 'tags')
        ;
        $child
            ->addChild('tax_rates', ['route' => 'sylius_admin_configuration'])
            ->setLabel('sylius.menu.admin.configuration.system.tax_rates')
            ->setLabelAttribute('icon', 'percent')
        ;

        if (!$child->hasChildren()) {
            $menu->removeChild('taxes');
        }
    }

    /**
     * @param ItemInterface $menu
     */
    private function addShippingMenu(ItemInterface $menu)
    {
        $child = $menu
            ->addChild('shipping')
            ->setLabel('sylius.menu.admin.configuration.shipping.header')
        ;

        $child
            ->addChild('shipping_categories', ['route' => 'sylius_admin_configuration'])
            ->setLabel('sylius.menu.admin.configuration.system.shipping_categories')
            ->setLabelAttribute('icon', 'tags')
        ;
        $child
            ->addChild('shipping_methods', ['route' => 'sylius_admin_configuration'])
            ->setLabel('sylius.menu.admin.configuration.system.shipping_methods')
            ->setLabelAttribute('icon', 'truck')
        ;

        if (!$child->hasChildren()) {
            $menu->removeChild('shipping');
        }
    }
}
