<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Rbac\Provider;

use Sylius\Component\Rbac\Exception\PermissionNotFoundException;
use Sylius\Component\Resource\Repository\ResourceRepositoryInterface;

/**
 * Default permission provider uses repository to find the permission.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class PermissionProvider implements PermissionProviderInterface
{
    /**
     * @var ResourceRepositoryInterface
     */
    protected $repository;

    /**
     * @param ResourceRepositoryInterface $repository
     */
    public function __construct(ResourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermission($code)
    {
        if (null === $permission = $this->repository->findOneBy(array('code' => $code))) {
            throw new PermissionNotFoundException($code);
        }

        return $permission;
    }
}
