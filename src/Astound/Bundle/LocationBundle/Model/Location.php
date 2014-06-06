<?php

/*
 * Mark Williams wrote this - ish
 */

namespace Astound\Bundle\LocationBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\AddressingBundle\Model\AddressInterface;
use Astound\Bundle\LocationBundle\Model\IPAddress;


/**
 * Location entity.
 *
 * @author Mark Williams
 */
class Location
{
    /**
     * Id.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Location fullName.
     *
     * @var string
     */
    protected $fullName;

    /**
     * Location shortName.
     *
     * @var string
     */
    protected $shortName;

    /**
     * Location address.
     *
     * @var AddressInterface
     */
    protected $address;

    /**
     * IPAddresses for this location.
     *
     * @var Collection|IPAddress[]
     */
    protected $IPAddresses;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Modification time.
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Deletion time.
     *
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->IPAddresses = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * {@inheritdoc}
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress(AddressInterface $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIPAddresses()
    {
        return $this->IPAddresses;
    }

    /**
     * {@inheritdoc}
     */
    public function hasIPAddresses()
    {
        return !$this->IPAddresses->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function addIPAddress(IPAddress $IPAddress)
    {
        if (!$this->hasIPAddress($IPAddress)) {
            $this->IPAddresses->add($IPAddress);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeIPAddress(IPAddress $IPAddress)
    {
        if ($this->hasIPAddress($IPAddress)) {
            $this->IPAddresses->removeElement($IPAddress);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasIPAddress(IPAddress $IPAddress)
    {
        return $this->IPAddresses->contains($IPAddress);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDeleted()
    {
        return null !== $this->deletedAt && new \DateTime() >= $this->deletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setDeletedAt(\DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

}
