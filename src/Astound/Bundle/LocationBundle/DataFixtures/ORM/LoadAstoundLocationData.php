<?php

/*
 * Mark Williams wrote this - ish
 */

namespace Astound\Bundle\LocationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Intl\Intl;

/**
 * Default country fixtures.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadAstoundLocationData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $countryRepository = $this->getCountryRepository();
        $countries = Intl::getRegionBundle()->getCountryNames();

        foreach ($countries as $isoName => $name) {
            $country = $countryRepository->createNew();

            $country->setName($name);
            $country->setIsoName($isoName);

            if ('US' === $isoName) {
                $this->addUsStates($country);
            }

            $manager->persist($country);

            $this->setReference('Sylius.Country.'.$isoName, $country);
        }

        $manager->flush();
    }
}