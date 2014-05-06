<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Currency\Model;

/**
 * Interface implemented by models referencing multiple currencies.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface CurrenciesAwareInterface
{
    /**
     * @return CurrencyInterface[]
     */
    public function getCurrencies();

    public function addCurrency(CurrencyInterface $currency);
    public function removeCurrency(CurrencyInterface $currency);
    public function hasCurrency(CurrencyInterface $currency);
}
