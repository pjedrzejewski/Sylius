<?php

/*
 *  Override of Sylius class by the same name
 */

namespace Astound\Bundle\CoreBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\UserController as SyliusUserController;


class UserController extends SyliusUserController
{
    public function createNew()
    {
        $resource = $this
            ->getRepository()
            ->createNew()
        ;

        $this->get('astound.manager.user')->setAddressDefaults($resource);

        return $resource;
    }

}
