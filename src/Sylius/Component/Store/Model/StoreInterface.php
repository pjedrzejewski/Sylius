<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Store\Model;

use Sylius\Component\Resource\Model\TimestampableInterface;

interface StoreInterface extends TimestampableInterface
{
    public function getCode();
    public function setCode($code);
    public function getName();
    public function setName($name);
    public function getDescription();
    public function setDescription($description);
    public function getUrl();
    public function setUrl($url);
    public function isDefault();
    public function setDefault($default);
}
