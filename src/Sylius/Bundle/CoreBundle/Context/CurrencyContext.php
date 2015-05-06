<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Context;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Sylius\Component\Currency\Context\CurrencyContext as BaseCurrencyContext;
use Sylius\Component\Storage\StorageInterface;
use Sylius\Component\User\Context\CustomerContextInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class CurrencyContext extends BaseCurrencyContext
{
    protected $customerContext;
    protected $settingsManager;
    protected $customerManager;

    public function __construct(
        StorageInterface $storage,
        CustomerContextInterface $customerContext,
        SettingsManagerInterface $settingsManager,
        ObjectManager $customerManager
    ) {
        $this->customerContext = $customerContext;
        $this->settingsManager = $settingsManager;
        $this->customerManager = $customerManager;

        parent::__construct($storage, $this->getDefaultCurrency());
    }

    public function getDefaultCurrency()
    {
        return $this->settingsManager->loadSettings('general')->get('currency');
    }

    public function getCurrency()
    {
        if ((null !== $customer = $this->customerContext->getCustomer()) && null !== $customer->getCurrency()) {
            return $customer->getCurrency();
        }

        return parent::getCurrency();
    }

    public function setCurrency($currency)
    {
        if (null === $customer = $this->customerContext->getCustomer()) {
            return parent::setCurrency($currency);
        }

        $customer->setCurrency($currency);

        $this->customerManager->persist($customer);
        $this->customerManager->flush();
    }
}
