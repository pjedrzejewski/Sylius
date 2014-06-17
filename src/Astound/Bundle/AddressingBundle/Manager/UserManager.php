<?php

/*
 *  Created by Mark J Williams
 *  Injected arguments:
 *          - @sylius.repository.country
 *          - @sylius.repository.province
 */

namespace Astound\Bundle\AddressingBundle\Manager;

// use Sylius\Component\Addressing\Model\Address;
use Astound\Bundle\AddressingBundle\Model\Address as Address;

class UserManager 
{

	private $country;

	private $state;

	private $address;

	public function __construct($country, $state)
	{
		// get a USA country Object
		$this->country = $country->find('248');
		// get a Minnesota Province Object
		$this->state = $state->find('24');

		// Set the country and province objects in an address Object
		$this->address = new Address();
		$this->address->setCountry($this->country);
        $this->address->setProvince($this->state);

	}

    public function setAddressDefaults($resource)
    {
		$resource->setShippingAddress($this->address);
		$resource->setBillingAddress($this->address);

        return $resource;
    }

}
