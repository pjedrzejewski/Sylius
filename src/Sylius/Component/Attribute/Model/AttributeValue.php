<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Attribute\Model;

/**
 * Attribute to subject relation.
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class AttributeValue implements AttributeValueInterface
{
    /**
     * Id.
     *
     * @var integer
     */
    protected $id;

    /**
     * Subject.
     *
     * @var AttributeSubjectInterface
     */
    protected $subject;

    /**
     * Attribute.
     *
     * @var AttributeInterface
     */
    protected $attribute;

    /**
     * Varchar value.
     *
     * @var string
     */
    protected $varchar;

    /**
     * Integer values.
     *
     * @var integer
     */
    protected $integer;

    /**
     * Decimal value.
     *
     * @var double
     */
    protected $decimal;

    /**
     * Boolean value.
     *
     * @var Boolean
     */
    protected $boolean;

    /**
     * Text value.
     *
     * @var string
     */
    protected $text;

    /**
     * Date values.
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Datetime values.
     *
     * @var \DateTime
     */
    protected $datetime;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject(AttributeSubjectInterface $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute(AttributeInterface $attribute)
    {
        if (is_object($this->attribute) && $attribute !== $this->attribute) {
            throw new \LogicException('Attribute has been already defined for this value.');
        }

        $this->attribute = $attribute;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        if (null === $this->attribute) {
            return null;
        }

        $method = 'get'.ucfirst($this->attribute->getStorage());

        return $this->$method();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->assertAttributeIsSet();

        $method = 'set'.ucfirst($this->attribute->getStorage());

        return $this->$method($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getVarchar()
    {
        return $this->varchar;
    }

    /**
     * {@inheritdoc}
     */
    public function setVarchar($varchar)
    {
        $this->varchar = $varchar;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * {@inheritdoc}
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDecimal()
    {
        return $this->decimal;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecimal($decimal)
    {
        $this->decimal = $decimal;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * {@inheritdoc}
     */
    public function setBoolean($boolean)
    {
        $this->boolean = $boolean;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateTime()
    {
        return $this->datetime;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateTime(\DateTime $datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $this->assertAttributeIsSet();

        return $this->attribute->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getPresentation()
    {
        $this->assertAttributeIsSet();

        return $this->attribute->getPresentation();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        $this->assertAttributeIsSet();

        return $this->attribute->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $this->assertAttributeIsSet();

        return $this->attribute->getConfiguration();
    }

    /**
     * @throws \BadMethodCallException When attribute is not set
     */
    protected function assertAttributeIsSet()
    {
        if (null === $this->attribute) {
            throw new \BadMethodCallException('The attribute is undefined, so you cannot access proxy methods.');
        }
    }
}
