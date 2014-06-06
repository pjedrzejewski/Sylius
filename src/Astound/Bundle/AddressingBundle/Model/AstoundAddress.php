<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Astound\Bundle\AddressingBundle\Model;

use Symfony\Component\Validator\ExecutionContextInterface;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Sylius\Component\Addressing\Model\Address as SyliusAddress;
/**
 * Default address model.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 */
class AstoundAddress extends SyliusAddress implements AstoundAddressInterface
{
 
    /**
     * Phone1.
     *
     * @var phone_number
     * @AssertPhoneNumber(defaultRegion="US")
     */
    protected $phone1;

    /**
     * Phone2.
     *
     * @var phone_number
     * @AssertPhoneNumber(defaultRegion="US")
     */
    protected $phone2;

    /**
     * {@inheritdoc}
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone2()
    {
        return $this->phone2;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }
}
