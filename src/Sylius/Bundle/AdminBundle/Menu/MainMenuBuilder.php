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
final class MainMenuBuilder extends AbstractAdminMenuBuilder
{
    const EVENT_NAME = 'sylius.menu.admin.main';

    /**
     * @return ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');

        $this->addSystemMenu($menu);

        $this->eventDispatcher->dispatch(self::EVENT_NAME, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     */
    private function addSystemMenu(ItemInterface $menu)
    {
        $child = $menu
            ->addChild('system')
            ->setLabel('sylius.menu.admin.main.system.header')
        ;

        $child
            ->addChild('configuration', ['route' => 'sylius_admin_configuration'])
            ->setLabel('sylius.menu.admin.main.system.configuration')
            ->setLabelAttribute('icon', 'cogs')
        ;

        if (!$child->hasChildren()) {
            $menu->removeChild('system');
        }
    }
}
