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

use Sylius\Component\Resource\Manager\ResourceManagerInterface;
use Sylius\Component\Resource\Repository\ResourceRepositoryInterface;
use Sylius\Component\User\Security\Generator\GeneratorInterface;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
class TokenProvider implements TokenProviderInterface
{
    /**
     * @var ResourceRepositoryInterface
     */
    private $repository;

    /**
     * @var ResourceManagerInterface
     */
    private $manager;

    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * @var integer
     */
    private $tokenLength;

    /**
     * @param ResourceRepositoryInterface $repository
     * @param ResourceManagerInterface    $manager
     * @param GeneratorInterface          $generator
     */
    public function __construct(ResourceRepositoryInterface $repository, ResourceManagerInterface $manager, GeneratorInterface $generator, $tokenLength)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->generator = $generator;
        $this->tokenLength = $tokenLength;
    }

    /**
     * {@inheritDoc}
     */
    public function generateUniqueToken()
    {
        do {
            $token = $this->generator->generate($this->tokenLength);
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
        $this->repository->disableFilter('softdeleteable');

        $isUsed = null !== $this->repository->findOneBy(array('confirmationToken' => $token));

        $this->repository->enableFilter('softdeleteable');

        return $isUsed;
    }
}
