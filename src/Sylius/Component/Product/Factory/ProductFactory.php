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

namespace Sylius\Component\Product\Factory;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductFactory implements ProductFactoryInterface
{
    /** @var FactoryInterface */
    private $factory;

    /** @var FactoryInterface */
    private $variantFactory;

    /** @var RepositoryInterface */
    private $familyRepository;

    public function __construct(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        RepositoryInterface $familyRepository
    ) {
        $this->factory = $factory;
        $this->variantFactory = $variantFactory;
        $this->familyRepository = $familyRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ProductInterface
    {
        return $this->factory->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function createWithVariant(): ProductInterface
    {
        $variant = $this->variantFactory->createNew();

        /** @var ProductInterface $product */
        $product = $this->factory->createNew();
        $product->addVariant($variant);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromFamily($familyCode): ProductInterface
    {
        $product = $this->createWithVariant();

        $family = $this->familyRepository->findOneBy(['code' => $familyCode]);

        if (null === $family) {
            throw new \InvalidArgumentException(sprintf('Product family with code "%s" does not exist!', $familyCode));
        }

        foreach ($family->getOptions() as $option) {
            $product->addOption($option);
        }

        $product->setCode($familyCode . '_');

        return $product;
    }
}
