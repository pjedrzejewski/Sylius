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

namespace Sylius\Bundle\CoreBundle\Collector;

use Sylius\Bundle\CoreBundle\Application\Kernel;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Context\CurrencyNotFoundException;
use Sylius\Component\Locale\Context\LocaleNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class SyliusCollector extends DataCollector
{
    /**
     * @param array $bundles
     * @param string $defaultLocaleCode
     */
    public function __construct(
        array $bundles,
        string $defaultLocaleCode
    ) {
        $this->data = [
            'version' => Kernel::VERSION,
            'extensions' => [
                'SyliusAdminApiBundle' => ['name' => 'API', 'enabled' => false],
                'SyliusAdminBundle' => ['name' => 'Admin', 'enabled' => false],
                'SyliusShopBundle' => ['name' => 'Shop', 'enabled' => false],
            ],
        ];

        foreach (array_keys($this->data['extensions']) as $bundleName) {
            if (isset($bundles[$bundleName])) {
                $this->data['extensions'][$bundleName]['enabled'] = true;
            }
        }
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->data['version'];
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->data['extensions'];
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'sylius_core';
    }
}
