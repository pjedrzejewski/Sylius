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

use Sylius\Component\Addressing\Model\AddressInterface as SyliusAddressInterface;

/**
 * Common address model interface.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 */
interface AstoundAddressInterface extends SyliusAddressInterface
{
    /**
     * Get phone1.
     *
     * @return string
     */
    public function getPhone1();

    /**
     * Set phone1.
     *
     * @param string $phone1
     */
    public function setPhone1($phone1);

    /**
     * Get phone2.
     *
     * @return string
     */
    public function getPhone2();

    /**
     * Set phone2.
     *
     * @param string $phone2
     */
    public function setPhone2($phone2);
}
