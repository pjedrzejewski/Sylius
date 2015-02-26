<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\User\Security;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
* @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
*/
class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param Container $container
     */
    public function __construct(RepositoryInterface $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function generateUniqueToken()
    {
        $token = null;

        do {
            $hash = sha1(microtime(true));
            $token = strtoupper(substr($hash, mt_rand(0, 33), 6));
        } while ($this->isUsedCode($token));

        return $token;
    }

    /**
     * @param string $token
     *
     * @return Boolean
     */
    protected function isUsedCode($token)
    {
        $this->manager->getFilters()->disable('softdeleteable');

        $isUsed = null !== $this->repository->findOneBy(array('confirmationToken' => $token));

        $this->manager->getFilters()->enable('softdeleteable');

        return $isUsed;
    }
}
