<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\GridBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;
use Webmozart\Assert\Assert;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class Driver implements DriverInterface
{
    const NAME = 'doctrine/orm';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource(array $configuration, Parameters $parameters)
    {
        Assert::keyExists($configuration, 'class');

        $repository = $this->entityManager->getRepository($configuration['class']);
        $queryBuilder = $repository->createListQueryBuilder();

        return new DataSource($queryBuilder);
    }
}
