<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Product\Model\ProductFamilyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductFamilyContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var RepositoryInterface */
    private $productFamilyRepository;

    /** @var FactoryInterface */
    private $productFamilyFactory;

    /** @var ObjectManager */
    private $objectManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $productFamilyRepository,
        FactoryInterface $productFamilyFactory,
        ObjectManager $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productFamilyRepository = $productFamilyRepository;
        $this->productFamilyFactory = $productFamilyFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given the store has (also) a product family :name
     * @Given the store has a product family :name with a code :code
     */
    public function theStoreHasAProductFamilyWithACode($name, $code = null)
    {
        $this->createProductFamily($name, $code);
    }

    /**
     * @param string $name
     * @param string|null $code
     *
     * @return ProductFamilyInterface
     */
    private function createProductFamily($name, $code = null)
    {
        /** @var ProductFamilyInterface $productFamily */
        $productFamily = $this->productFamilyFactory->createNew();
        $productFamily->setName($name);
        $productFamily->setCode($code ?: StringInflector::nameToCode($name));

        $this->sharedStorage->set('product_family', $productFamily);
        $this->productFamilyRepository->add($productFamily);

        return $productFamily;
    }
}
