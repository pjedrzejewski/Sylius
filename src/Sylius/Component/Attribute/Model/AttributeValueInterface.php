<?php

/*
 * This file is part of the Sylius package.
 *
 * (c); Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\Model;

/**
 * Attribute value model.
 *
 * This model associates the attribute with its value on the object.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
interface AttributeValueInterface
{
    /**
     * Get subject.
     *
     * @return AttributeSubjectInterface
     */
    public function getSubject();

    /**
     * Set subject.
     *
     * @param AttributeSubjectInterface|null $subject
     */
    public function setSubject(AttributeSubjectInterface $subject = null);

    /**
     * Get attribute.
     *
     * @return AttributeInterface
     */
    public function getAttribute();

    /**
     * Set attribute.
     *
     * @param AttributeInterface $attribute
     */
    public function setAttribute(AttributeInterface $attribute);

    /**
     * Get attribute value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set attribute value.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Get varchar value.
     *
     * @return string
     */
    public function getVarchar();

    /**
     * Set varchar value.
     *
     * @param string $varchar
     */
    public function setVarchar($varchar);

    /**
     * Get integer value.
     *
     * @return integer
     */
    public function getInteger();

    /**
     * Set integer value.
     *
     * @param integer $integer
     */
    public function setInteger($integer);

    /**
     * Get decimal value.
     *
     * @return double
     */
    public function getDecimal();

    /**
     * Set decimal value.
     *
     * @param double $decimal
     */
    public function setDecimal($decimal);

    /**
     * Get boolean value.
     *
     * @return Boolean
     */
    public function getBoolean();

    /**
     * Set boolean value.
     *
     * @param Boolean $boolean
     */
    public function setBoolean($boolean);

    /**
     * Get text value.
     *
     * @return string
     */
    public function getText();

    /**
     * Set text value.
     *
     * @param string $text
     */
    public function setText($text);

    /**
     * Get date value.
     *
     * @return DateTime
     */
    public function getDate();

    /**
     * Set date value.
     *
     * @param DateTime $date
     */
    public function setDate(\DateTime $date);

    /**
     * Get datetime value.
     *
     * @return DateTime
     */
    public function getDateTime();

    /**
     * Set datetime value.
     *
     * @param DateTime $datetime
     */
    public function setDateTime(\DateTime $datetime);

    /**
     * Proxy method to access the name from real attribute.
     *
     * @return string
     */
    public function getName();

    /**
     * Proxy method to access the presentation from real attribute.
     *
     * @return string
     */
    public function getPresentation();

    /**
     * The type of the attribute.
     *
     * @return string
     */
    public function getType();

    /**
     * Get attribute configuration.
     *
     * @return array
     */
    public function getConfiguration();
}
