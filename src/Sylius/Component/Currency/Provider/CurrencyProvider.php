<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Currency\Provider;

use Sylius\Component\Resource\Repository\ResourceRepositoryInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class CurrencyProvider implements CurrencyProviderInterface
{
    /**
     * @var ResourceRepositoryInterface
     */
    protected $currencyRepository;

    /**
     * @param ResourceRepositoryInterface $currencyRepository
     */
    public function __construct(ResourceRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableCurrencies()
    {
        return $this->currencyRepository->findBy(array('enabled' => true));
    }
}
