<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle\Provider;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\User\Model\UserInterface as SyliusUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    public function __construct(RepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;    
    }

    public function loadUserByUsername($username)
    {

        $user = $this->userRepository->findOneBy(array('username' => $username));

        if (!$user) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SyliusUserInterface) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Sylius\Component\User\Model\UserInterface';
    }
}
