<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\TranslationBundle\Form\Extension;

use Sylius\Bundle\TranslationBundle\Form\EventSubscriber\AddMissingTranslationsFormSubscriber;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Translation\Provider\LocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class TranslatableResourceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var FactoryInterface
     */
    private $translationFactory;

    /**
     * @var LocaleProviderInterface
     */
    private $localesProvider;

    /**
     * @param MetadataInterface $metadata
     * @param FactoryInterface $translationFactory
     * @param LocaleProviderInterface $localesProvider
     */
    public function __construct(MetadataInterface $metadata, FactoryInterface $translationFactory, LocaleProviderInterface $localesProvider)
    {
        $this->metadata = $metadata;
        $this->translationFactory = $translationFactory;
        $this->localesProvider = $localesProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddMissingTranslationsFormSubscriber($this->translationFactory, $this->localesProvider));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return sprintf('%s_%s', $this->metadata->getApplicationName(), $this->metadata->getName());
    }
}
