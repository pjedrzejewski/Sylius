<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle\EventListener;

use Sylius\Bundle\UserBundle\Reloader\UserReloaderInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\User\Canonicalizer\CanonicalizerInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Component\User\Security\PasswordUpdaterInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * User update listener.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class UserUpdateListener
{
    /**
     * @var UserReloaderInterface
     */
    protected $userReloader;
    
    /**
     * @var PasswordUpdaterInterface
     */
    protected $passwordUpdater;

    /**
     * @var CanonicalizerInterface
     */
    protected $canonicalizer;

    public function __construct(UserReloaderInterface $userReloader, PasswordUpdaterInterface $passwordUpdater, CanonicalizerInterface $canonicalizer)
    {
        $this->userReloader = $userReloader;
        $this->passwordUpdater = $passwordUpdater;
        $this->canonicalizer = $canonicalizer;
    }

    public function processUser(GenericEvent $event)
    {
        $user = $event->getSubject();

        if (!$this->supports($user)) {
            throw new UnexpectedTypeException(
                $user,
                'Sylius\Component\User\Model\UserInterface'
            );
        }

        $this->userReloader->reloadUser($user);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $item = $args->getEntity();

        if (!$this->supports($item)) {
            return;
        }

        if (null !== $item->getPlainPassword()) {
            $this->passwordUpdater->updatePassword($item);
        }

        $item->setUsernameCanonical($this->canonicalizer->canonicalize($item->getUsername()));
        $item->setEmailCanonical($this->canonicalizer->canonicalize($item->getEmail()));
    }

    private function supports($item)
    {
        return $item instanceof UserInterface;
    }
}
